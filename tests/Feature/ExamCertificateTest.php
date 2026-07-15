<?php

namespace Tests\Feature;

use App\Http\Controllers\Students\ExamController;
use Illuminate\Http\Request;
use Tests\TestCase;

class ExamCertificateTest extends TestCase
{
    public function test_it_returns_empty_payload_when_no_certificate_url_is_provided()
    {
        $controller = new ExamController();
        $response = $controller->getCertificateBase64(new Request());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(null, json_decode($response->getContent(), true)['base64'] ?? null);
    }
}
