<?php

namespace App\Form\Draw;

use App\Form\FormAbstract;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DrawAddForm extends FormAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'draw.form.labels.name',
                'required' => true,
                'attr' => [
                    'autocomplete' => 'off'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'buttons.save',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }
}