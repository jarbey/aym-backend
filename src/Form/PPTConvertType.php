<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 15/08/2018
 * Time: 16:07
 */

namespace App\Form;

use App\Model\PPTConvertModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PPTConvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ppt', FileType::class, array('label' => 'PDF File'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PPTConvertModel::class,
        ));
    }
}