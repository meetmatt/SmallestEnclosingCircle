<?php

namespace MeetMatt\SmallestEnclosingCircle\Tests;

use MeetMatt\SmallestEnclosingCircle\Circle;
use MeetMatt\SmallestEnclosingCircle\Point;
use MeetMatt\SmallestEnclosingCircle\SmallestEnclosingCircle;
use PHPUnit\Framework\TestCase;

class SmallestEnclosingCircleTest extends TestCase
{
    const EPSILON = 1e-12;

    public function testMatchingNaiveAlgorithm()
    {
        $trials = 100;
        for ($i = 0; $i < $trials; $i++) {
            $points = $this->makeRandomPoints(mt_rand(0, 30) + 1);
            $reference = $this->smallestEnclosingCircleNaive($points);
            $actual = SmallestEnclosingCircle::makeCircle($points);
            $this->assertNotNull($reference);
            $this->assertNotNull($actual);
            $this->assertEquals($reference->getCenter()->getX(), $actual->getCenter()->getX(), self::EPSILON);
            $this->assertEquals($reference->getCenter()->getY(), $actual->getCenter()->getY(), self::EPSILON);
            $this->assertEquals($reference->getRadius(), $actual->getRadius(), self::EPSILON);
        }
    }

    /**
     * @param int $n
     *
     * @return Point[]
     */
    private function makeRandomPoints($n)
    {
        $result = [];
        $random = mt_rand() / mt_getrandmax();
        if ($random < 0.2) {
            // Discrete lattice (to have a chance of duplicated points)
            for ($i = 0; $i < $n; $i++) {
                $result[] = new Point(mt_rand(0, 10), mt_rand(0, 10));
            }
        } else {
            // Gaussian distribution
            for ($i = 0; $i < $n; $i++) {
                $result[] = new Point($this->gaussian(), $this->gaussian());
            }
        }
        return $result;
    }

    /**
     * @param int $mean
     * @param int $sd
     *
     * @return float
     */
    private function gaussian($mean = 0, $sd = 1)
    {
        $x = mt_rand() / mt_getrandmax();
        $y = mt_rand() / mt_getrandmax();
        return sqrt(-2 * log($x)) * cos(2 * M_PI * $y) * $sd + $mean;
    }


    /**
     * Returns the smallest enclosing circle in O(n^4) time using the naive algorithm.
     *
     * @param Point[] $points
     *
     * @return Circle|null
     */
    private function smallestEnclosingCircleNaive(array $points)
    {
        $size = count($points);

        // Degenerate cases
        if ($size === 0) {
            return null;
        }

        if ($size === 1) {
            return new Circle($points[0], 0);
        }

        // Try all unique pairs
        /** @var Circle $result */
        $result = null;
        for ($i = 0; $i < $size; $i++) {
            for ($j = $i + 1; $j < $size; $j++) {
                $c = SmallestEnclosingCircle::makeDiameter($points[$i], $points[$j]);
                if (($result === null || $c->getRadius() < $result->getRadius()) && $c->containsAll($points)) {
                    $result = $c;
                }
            }
        }

        if ($result !== null) {
            // This optimization is not mathematically proven
            return $result;
        }

        // Try all unique triples
        for ($i = 0; $i < $size; $i++) {
            for ($j = $i + 1; $j < $size; $j++) {
                for ($k = $j + 1; $k < $size; $k++) {
                    $c = SmallestEnclosingCircle::makeCircumcircle($points[$i], $points[$j], $points[$k]);
                    if ($c !== null && ($result === null || $c->getRadius() < $result->getRadius()) && $c->containsAll(
                            $points
                        )) {
                        $result = $c;
                    }
                }
            }
        }

        return $result;
    }
}
