<?php

namespace SB\Korona\Type;

class ProductGroup
{

    /**
     * @var \SB\Korona\Type\String50Seq
     */
    private $products;

    /**
     * @var \SB\Korona\Type\String50Seq
     */
    private $templates;

    /**
     * @var \SB\Korona\Type\ProductClassifierSeq
     */
    private $classifiers;

    /**
     * @return \SB\Korona\Type\String50Seq
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param \SB\Korona\Type\String50Seq $products
     * @return ProductGroup
     */
    public function withProducts($products)
    {
        $new = clone $this;
        $new->products = $products;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\String50Seq
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param \SB\Korona\Type\String50Seq $templates
     * @return ProductGroup
     */
    public function withTemplates($templates)
    {
        $new = clone $this;
        $new->templates = $templates;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ProductClassifierSeq
     */
    public function getClassifiers()
    {
        return $this->classifiers;
    }

    /**
     * @param \SB\Korona\Type\ProductClassifierSeq $classifiers
     * @return ProductGroup
     */
    public function withClassifiers($classifiers)
    {
        $new = clone $this;
        $new->classifiers = $classifiers;

        return $new;
    }


}

