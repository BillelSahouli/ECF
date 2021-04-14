<?php

namespace App\Form;

use App\Entity\BankAccount;
use App\Entity\User;
use App\Repository\BankAccountRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class BankAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uniqueId', NumberType::class,[
                'label' => 'Id unique',
                'required' => true,
                'attr' => [
                    'placeholder' => 'insérez le chiffre 7 pour un ID a 8 chiffre'
                ]
            ])
            ->add('currentAccount', NumberType::class,[
                'label' => 'Compte courant',
                'constraints' => new Positive([]),
                'required' => true,
                'attr' => [
                    'placeholder' => 'insérez le montant de votre choix'
                ]
            ])
            ->add('bookletA', NumberType::class,[
                'label' => 'Livret A',
                'constraints' => new Positive([]),
                'required' => true,
                'attr' => [
                    'placeholder' => 'insérez le montant de votre choix'
                ]
            ])
            ->add('accountIsActive', CheckboxType::class, [
                'label' => 'Activez le compte'
            ])
            ->add('userBelongs',EntityType::class, [
                'label' => 'Quel compte valider ?',
                'required' => true,
                'class'=> User::class,
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
