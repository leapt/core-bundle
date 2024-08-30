<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use ReCaptcha\ReCaptcha;
use ReCaptcha\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RecaptchaV3Validator extends ConstraintValidator
{
    private string $secretKey;

    /**
     * ContainsRecaptchaValidator constructor.
     */
    public function __construct(
        private bool $enabled,
        ?string $secretKey,
        private float $scoreThreshold,
        private RequestStack $requestStack,
    ) {
        $this->secretKey = $secretKey;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$this->enabled) {
            return;
        }

        if (!class_exists(ReCaptcha::class)) {
            throw new \Exception(\sprintf('The "google/recaptcha" package is required to use "%s". Try running "composer require google/recaptcha".', static::class));
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

        $response = $this->verifyToken($value);
        if ($response->isSuccess()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $this->formatValue($value))
            ->setParameter('{{ score }}', $this->formatValue($response->getScore()))
            ->setParameter('{{ errorCodes }}', $this->formatValues($response->getErrorCodes()))
            ->addViolation();
    }

    private function verifyToken(string $token): Response
    {
        $remoteIp = $this->requestStack->getCurrentRequest()?->getClientIp();
        $recaptcha = new ReCaptcha($this->secretKey);

        return $recaptcha
            ->setScoreThreshold($this->scoreThreshold)
            ->verify($token, $remoteIp);
    }
}
