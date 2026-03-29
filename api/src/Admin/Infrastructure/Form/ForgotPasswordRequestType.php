<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Form;

use App\Admin\Application\Dto\ForgotPasswordRequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ForgotPasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'forgot_password_request.form.field.email.label',
                'attr' => [
                    'autofocus' => true,
                    'autocomplete' => 'username',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'forgot_password_request.form.action.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForgotPasswordRequestDto::class,
            'translation_domain' => 'admin',
        ]);
    }
}
