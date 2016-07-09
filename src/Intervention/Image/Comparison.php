<?php

namespace Intervention\Image;

class Comparison
{
    /**
     * Metric to compare
     *
     * @var integer
     */
    private $metric;

    /**
     * Score of the comparison
     *
     * @var float
     */
    private $score;

    /**
     * An image showing the difference between the two images
     *
     * @var Image
     */
    private $diffImage;

    /**
     * Create a new Comparison
     *
     * @param integer    $metric
     * @param float      $score
     * @param Image|null $diffImage
     */
    public function __construct($metric, $score, Image $diffImage = null)
    {
        $this->metric = $metric;
        $this->score = $score;
        $this->diffImage = $diffImage;
    }

    /**
     * @return int
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return Image
     */
    public function getDiffImage()
    {
        return $this->diffImage;
    }
}
