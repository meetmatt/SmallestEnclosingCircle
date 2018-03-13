<?php

namespace MeetMatt\SmallestEnclosingCircle;

class Circle
{
    const MULTIPLICATIVE_EPSILON = 1 + 1e-14;

    /**
     * @var Point
     */
    private $center;

    /**
     * @var float
     */
    private $radius;

    /**
     * @param Point $center
     * @param float $radius
     */
    public function __construct(Point $center, $radius)
    {
        $this->center = $center;
        $this->radius = $radius;
    }

    /**
     * @return Point
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param Point $point
     *
     * @return bool
     */
    public function contains(Point $point)
    {
        return $this->center->getDistance($point) <= $this->radius * self::MULTIPLICATIVE_EPSILON;
    }

    /**
     * @param Point[] $points
     *
     * @return bool
     */
    public function containsAll(array $points)
    {
        foreach ($points as $point) {
            if (!$this->contains($point)) {
                return false;
            }
        }

        return true;
    }

    public function __toString()
    {
        return sprintf('Circle(x=%f, y=%f, r=%f', $this->center->getX(), $this->center->getY(), $this->radius);
    }
}
