<?php

namespace App\Infrastructure\Persistence\Doctrine\DataFixtures;

use App\Domain\Model\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $now = new \DateTime();

        $article = (new Article())
            ->setTitle("Tesla Invests $1.5 Billion in Bitcoin")
            ->setBody("Elon Musk’s company said it may start accepting the cryptocurrency as a payment method for its products, sending the price of bitcoin soaring")
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $manager->persist($article);

        $article = (new Article())
            ->setTitle("Malls Spent Billions on Theme Parks to Woo Shoppers. It Made Matters Worse.")
            ->setBody("Go-karts, laser tag and ropes courses aren’t very popular during a pandemic, even when they’re open. For big operators who thought entertainment was a solution to a declining business, the bills are coming due to...")
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $manager->persist($article);

        $manager->flush();
    }
}
