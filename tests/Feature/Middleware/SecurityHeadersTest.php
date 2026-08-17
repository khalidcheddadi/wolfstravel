<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /** @test */
    public function it_sets_security_headers()
    {
        $response = $this->get('/');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        echo "All security headers are present\n";
    }

    /** @test */
    public function it_does_not_duplicate_csp_directives()
    {
        $response = $this->get('/');
        $csp = $response->headers->get('Content-Security-Policy');
        $count = substr_count($csp, 'connect-src');
        $this->assertLessThanOrEqual(1, $count, 'Duplicate connect-src directive found');
        echo "CSP does not contain duplicate directives\n";
    }
}