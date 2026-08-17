<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_email_and_redirects_with_success_message(): void
    {
        Mail::fake();

        $response = $this->from('/contact')
            ->post('/contact', [
                'name' => 'Youssef Test',
                'email' => 'youssef@example.com',
                'subject' => 'Booking question',
                'message' => 'I would like to know more about the destination packages.',
            ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('success');

        Mail::assertSent(ContactMessageMail::class, function (ContactMessageMail $mail) {
            $this->assertSame('Youssef Test', $mail->name);
            $this->assertSame('youssef@example.com', $mail->email);
            $this->assertSame('Booking question', $mail->subject);
            $this->assertStringContainsString('destination packages', $mail->message);

            return true;
        });
    }
}
