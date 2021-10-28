<?php

namespace Leapt\CoreBundle\Validator\Constraints;

use Psr\Log\LoggerInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RecaptchaV3Validator extends ConstraintValidator
{
    private bool $enabled;

    private string $secretKey;

    private float $scoreThreshold;

    private RequestStack $requestStack;

    private LoggerInterface $logger;

    /**
     * ContainsRecaptchaValidator constructor.
     */
    public function __construct(
        bool $enabled,
        ?string $secretKey,
        float $scoreThreshold,
        RequestStack $requestStack,
        LoggerInterface $logger
    ) {
        $this->enabled = $enabled;
        $this->secretKey = $secretKey;
        $this->scoreThreshold = $scoreThreshold;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$this->enabled) {
            return;
        }

        if (!class_exists(ReCaptcha::class)) {
            throw new \Exception(sprintf('The "google/recaptcha" package is required to use "%s". Try running "composer require google/recaptcha".', static::class));
        }

        if (!$constraint instanceof RecaptchaV3) {
            throw new UnexpectedTypeException($constraint, RecaptchaV3::class);
        }

        if (null === $value) {
            $value = '';
        }

        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!$this->isTokenValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    private function isTokenValid(string $token): bool
    {
        try {
            $remoteIp = $this->requestStack->getCurrentRequest()->getClientIp();
            $recaptcha = new ReCaptcha($this->secretKey);

            $response = $recaptcha
                ->setScoreThreshold($this->scoreThreshold)
                ->verify($token, $remoteIp);

            return $response->isSuccess();
        } catch (\Exception $exception) {
            $this->logger->error(
                'reCAPTCHA validator error: ' . $exception->getMessage(),
                [
                    'exception' => $exception,
                ]
            );

            return false;
        }
    }
}
