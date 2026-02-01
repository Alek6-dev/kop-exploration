<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\Email;

use App\Shared\Application\Command\AsCommandHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsCommandHandler]
final readonly class SendEmailCommandHandler
{
    public function __construct(
        private MailerInterface $mailer,
        #[Autowire(param: 'mailer.sender_email')]
        public string $emailSenderAddress,
        #[Autowire(param: 'mailer.sender_name')]
        public string $emailSenderName,
    ) {
    }

    public function __invoke(SendEmailCommand $command): bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->emailSenderAddress, $this->emailSenderName))
            ->to($command->emailToAddress)
            ->subject($command->subject)
            ->htmlTemplate($command->template)
            ->context($command->context)
        ;

        $this->mailer->send($email);

        return true;
    }
}
