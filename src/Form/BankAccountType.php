<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uniqueId')
            ->add('currentAccount')
            ->add('bookletA')
            ->add('accountIsActive')
            ->add('userBelongs',EntityType::class, [
                'label' => 'Quel compte valider ?',
                'required' => true,
                'class' => User::class,
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
        ]);
    }
}
