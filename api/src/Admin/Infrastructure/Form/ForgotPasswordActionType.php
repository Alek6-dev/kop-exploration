<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Form;

use App\Admin\Application\Dto\ForgotPasswordActionDto;
use App\Admin\Infrastructure\Field\RepeatedPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ForgotPasswordActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedPasswordType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'forgot_password_action.form.action.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForgotPasswordActionDto::class,
            'translation_domain' => 'admin',
        ]);
    }
}
