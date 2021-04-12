<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class IsDeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isDelete', CheckboxType::class, [
                'label' => 'Cochez'
            ])
            ->add('signatureDeleteAccount', FileType::class, [
                'label' => "Signature",
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => "insérez une photo de signature avec piece d'identité"
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Inserez un fichier valide de type : pnj/jpeg ou un pdf'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider la demande'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
