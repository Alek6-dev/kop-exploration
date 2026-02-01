<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\TextAlign;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Contracts\Translation\TranslatableInterface;

final class CsvFileField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, TranslatableInterface|string|false $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(CsvFileFormType::class)
            ->addJsFiles(Asset::fromEasyAdminAssetPackage('field-file-upload.js'))
            ->setColumns('col-md-7 col-xxl-5')
            ->setTextAlign(TextAlign::CENTER)
        ;
    }
}
