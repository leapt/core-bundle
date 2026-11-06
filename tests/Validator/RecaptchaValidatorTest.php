<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Validator;

use Leapt\CoreBundle\Validator\Constraints\Recaptcha;
use Leapt\CoreBundle\Validator\Constraints\RecaptchaValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class RecaptchaValidatorTest extends ConstraintValidatorTestCase
{
    private bool $enabled = true;
    private ?string $privateKey = null;
    private bool $verifyHost = false;
    private RequestStack $requestStack;

    /** @var array{url: string, data: array}|null */
    private ?array $sentRequest = null;

    /** Fake response body returned by the reCAPTCHA server; null to send a real request to $realRequestUrl */
    private false|string|null $serverResponse = null;
    private string $realRequestUrl = '';

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->requestStack->push(Request::create('https://localhost/', 'POST', ['g-recaptcha-response' => 'user_answer']));

        parent::setUp();
    }

    public function testDisabledWithoutPrivateKeyIsValid(): void
    {
        $this->enabled = false;
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate(null, new Recaptcha());

        $this->assertNoViolation();
    }

    public function testEnabledWithoutPrivateKeyThrowsException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageIs('The "leapt_core.recaptcha.private_key" option must be set to use reCAPTCHA validation.');

        $this->validator->validate(null, new Recaptcha());
    }

    public function testSecretIsSentInRequestBody(): void
    {
        $this->serverResponse = '{"success": true}';
        $this->validateAnswer();

        $this->assertNoViolation();
        self::assertSame(RecaptchaValidator::RECAPTCHA_VERIFY_SERVER . '/recaptcha/api/siteverify', $this->sentRequest['url']);
        self::assertSame(['secret' => 'private_key', 'remoteip' => '127.0.0.1', 'response' => 'user_answer'], $this->sentRequest['data']);
    }

    public function testEmptyAnswerIsInvalidWithoutRequest(): void
    {
        $this->requestStack->pop();
        $this->requestStack->push(Request::create('https://localhost/', 'POST'));
        $this->validateAnswer();

        self::assertNull($this->sentRequest);
        $this->buildViolation((new Recaptcha())->message)->assertRaised();
    }

    #[DataProvider('provideInvalidServerResponses')]
    public function testInvalidServerResponseIsInvalid(false|string $serverResponse): void
    {
        $this->serverResponse = $serverResponse;
        $this->validateAnswer();

        $this->buildViolation((new Recaptcha())->message)->assertRaised();
    }

    public static function provideInvalidServerResponses(): iterable
    {
        yield 'unreachable server' => [false];
        yield 'invalid JSON' => ['<html>Service Unavailable</html>'];
        yield 'JSON without success key' => ['{"error-codes": ["internal-error"]}'];
        yield 'unsuccessful answer' => ['{"success": false}'];
    }

    public function testUnreachableServerIsInvalidWithoutWarning(): void
    {
        // Nothing listens on port 1, so the real request fails immediately
        $this->realRequestUrl = 'http://127.0.0.1:1/recaptcha/api/siteverify';
        $this->validateAnswer();

        $this->buildViolation((new Recaptcha())->message)->assertRaised();
    }

    public function testHostnameMismatchIsInvalid(): void
    {
        $this->verifyHost = true;
        $this->serverResponse = '{"success": true, "hostname": "example.com"}';
        $this->validateAnswer();

        $this->buildViolation((new Recaptcha())->invalidHostMessage)->assertRaised();
    }

    public function testHostnameMatchIsValid(): void
    {
        $this->verifyHost = true;
        $this->serverResponse = '{"success": true, "hostname": "localhost"}';
        $this->validateAnswer();

        $this->assertNoViolation();
    }

    protected function createValidator(): RecaptchaValidator
    {
        $test = $this;

        return new class ($this->enabled, $this->privateKey, $this->requestStack, ['host' => null, 'port' => null, 'auth' => null], $this->verifyHost, $test) extends RecaptchaValidator {
            public function __construct(bool $enabled, ?string $privateKey, RequestStack $requestStack, array $httpProxy, bool $verifyHost, private RecaptchaValidatorTest $test)
            {
                parent::__construct($enabled, $privateKey, $requestStack, $httpProxy, $verifyHost);
            }

            protected function httpPost(string $url, array $data): false|string
            {
                return $this->test->handleHttpPost($url, $data, fn(string $realUrl) => parent::httpPost($realUrl, $data));
            }
        };
    }

    /**
     * @internal
     *
     * @param \Closure(string): (false|string) $sendRealRequest
     */
    public function handleHttpPost(string $url, array $data, \Closure $sendRealRequest): false|string
    {
        $this->sentRequest = ['url' => $url, 'data' => $data];

        return $this->serverResponse ?? $sendRealRequest($this->realRequestUrl);
    }

    private function validateAnswer(): void
    {
        $this->privateKey = 'private_key';
        $this->validator = $this->createValidator();
        $this->validator->initialize($this->context);

        $this->validator->validate(null, new Recaptcha());
    }
}
