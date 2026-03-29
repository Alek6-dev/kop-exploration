<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RepeatedPasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'forgot_password_action.form.field.password.first_options.label',
                'help' => 'forgot_password_action.form.field.password.first_options.help',
            ],
            'second_options' => [
                'label' => 'forgot_password_action.form.field.password.second_options.label',
            ],
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
            ],
            'translation_domain' => 'security',
        ]);
    }

    public function getParent(): string
    {
        return RepeatedType::class;
    }
}
