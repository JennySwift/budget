<?php

use App\Models\Totals\BasicTotal;

/**
 * Class BasicTotalTest
 */
class BasicTotalTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $basicTotals = new BasicTotal();

        $this->assertInstanceOf('App\Models\Totals\BasicTotal', $basicTotals);
        $this->assertNotNull($basicTotals->transactions);
        $this->assertNotNull($basicTotals->debit);
        $this->assertNotNull($basicTotals->credit);
        $this->assertNotNull($basicTotals->reconciledSum);
        $this->assertNotNull($basicTotals->expensesWithoutBudget);
        $this->assertNotNull($basicTotals->savings);
    }

    /**
     * @test
     */
    public function it_can_be_casted_to_an_array()
    {
        $basicTotals = new BasicTotal();
        $toArray = $basicTotals->toArray();

        $this->assertInstanceOf('App\Models\Totals\BasicTotal', $basicTotals);
        $this->assertTrue(is_array($toArray));
        $this->assertArrayHasKey('debit', $toArray);
        $this->assertArrayHasKey('credit', $toArray);
        $this->assertArrayHasKey('reconciledSum', $toArray);
        $this->assertArrayHasKey('expensesWithoutBudget', $toArray);
        $this->assertArrayHasKey('savings', $toArray);
        $this->assertArrayHasKey('balance', $toArray);
    }
}