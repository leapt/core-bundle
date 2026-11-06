<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

class RecaptchaValidator extends ConstraintValidator
{
    /**
     * The reCAPTCHA server URL's.
     */
    public const string RECAPTCHA_VERIFY_SERVER = 'https://www.google.com';

    /**
     * Timeout (in seconds) of the request sent to the reCAPTCHA server.
     */
    private const int TIMEOUT = 10;

    public function __construct(
        protected bool $enabled,
        protected ?string $privateKey,
        protected RequestStack $requestStack,
        protected array $httpProxy,
        protected bool $verifyHost,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        \assert($constraint instanceof Recaptcha);

        // if recaptcha is disabled, always valid
        if (!$this->enabled) {
            return;
        }

        if (null === $this->privateKey || '' === $this->privateKey) {
            throw new \LogicException('The "leapt_core.recaptcha.private_key" option must be set to use reCAPTCHA validation.');
        }

        // define variable for recaptcha check answer
        $mainRequest = $this->requestStack->getMainRequest();
        $remoteIp = $mainRequest->getClientIp();
        $answer = $mainRequest->request->get('g-recaptcha-response');

        // Verify user response with Google
        $response = $this->checkAnswer($this->privateKey, $remoteIp, $answer);

        if (null === $response || true !== ($response['success'] ?? null)) {
            $this->context->addViolation($constraint->message);
        }
        // Perform server side hostname check
        elseif ($this->verifyHost && $mainRequest->getHost() !== ($response['hostname'] ?? null)) {
            $this->context->addViolation($constraint->invalidHostMessage);
        }
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct.
     *
     * @throws ValidatorException When missing remote ip
     *
     * @return array<string, mixed>|null The decoded response, or null if the answer is empty or the reCAPTCHA server could not be reached
     */
    private function checkAnswer(string $privateKey, ?string $remoteIp, ?string $answer): ?array
    {
        if (null === $remoteIp || '' === $remoteIp) {
            throw new ValidatorException('For security reasons, you must pass the remote ip to reCAPTCHA');
        }

        // discard spam submissions
        if (null === $answer || '' === $answer) {
            return null;
        }

        $response = $this->httpPost(self::RECAPTCHA_VERIFY_SERVER . '/recaptcha/api/siteverify', [
            'secret'   => $privateKey,
            'remoteip' => $remoteIp,
            'response' => $answer,
        ]);

        if (false === $response) {
            return null;
        }

        $response = json_decode($response, true);

        return \is_array($response) ? $response : null;
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server.
     *
     * @return false|string The response body, or false if the request failed
     */
    protected function httpPost(string $url, array $data): false|string
    {
        // Silenced so that network failures are reported as a validation violation instead of a PHP warning
        return @file_get_contents($url, false, $this->getResourceContext($data));
    }

    /**
     * @return resource
     */
    private function getResourceContext(array $data)
    {
        // The "http" options are also used for HTTPS requests
        $options = [
            'method'  => 'POST',
            'header'  => ['Content-Type: application/x-www-form-urlencoded'],
            'content' => http_build_query($data),
            'timeout' => self::TIMEOUT,
        ];

        if (null !== $this->httpProxy['host'] && null !== $this->httpProxy['port']) {
            $options['proxy'] = \sprintf('tcp://%s:%s', $this->httpProxy['host'], $this->httpProxy['port']);
            $options['request_fulluri'] = true;

            if (null !== $this->httpProxy['auth']) {
                $options['header'][] = \sprintf('Proxy-Authorization: Basic %s', base64_encode($this->httpProxy['auth']));
            }
        }

        return stream_context_create(['http' => $options]);
    }
}
