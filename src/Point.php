<?php

namespace MeetMatt\SmallestEnclosingCircle;

class Point
{
    /**
     * @var float
     */
    private $x;

    /**
     * @var float
     */
    private $y;

    /**
     * @param float $x
     * @param float $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param self $point
     *
     * @return float
     */
    public function getDistance(self $point)
    {
        return hypot($this->x - $point->getX(), $this->y - $point->getY());
    }

    /**
     * @param self $point
     *
     * @return self
     */
    public function subtract(self $point)
    {
        return new self($this->x - $point->getX(), $this->y - $point->getY());
    }

    /**
     * @param self $point
     *
     * @return float
     */
    public function cross(self $point)
    {
        return $this->x * $point->getY() - $this->y * $point->getX();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Point(%f, %f)', $this->x, $this->y);
    }
}
