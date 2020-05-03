<?php

namespace YazanStash\LaravelDebounce\Tests;

use YazanStash\LaravelDebounce\DebounceReport;

class DebounceReportTest extends \Orchestra\Testbench\TestCase
{
    /** @test */
    public function hydrates_an_array_into_a_report_instance()
    {
        $report = new DebounceReport($this->fakeReportData());

        $this->assertInstanceOf(DebounceReport::class, $report);
    }

    /** @test */
    public function test_email_method()
    {
        $report = new DebounceReport($this->fakeReportData());

        $this->assertEquals('stashyazan@gmail.com', $report->email());
    }

    /** @test */
    public function test_isRole_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['role' => 'false']]));
        $this->assertFalse($report->isRole());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['role' => 'true']]));
        $this->assertTrue($report->isRole());
    }

    /** @test */
    public function test_isFreeEmail_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['free_email' => 'false']]));
        $this->assertFalse($report->isFreeEmail());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['free_email' => 'true']]));
        $this->assertTrue($report->isFreeEmail());
    }

    /** @test */
    public function test_isTransactionable_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['send_transactional' => '0']]));
        $this->assertFalse($report->isTransactionable());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['send_transactional' => '1']]));
        $this->assertTrue($report->isTransactionable());
    }

    /** @test */
    public function test_correctedAddress_method()
    {
        $report = new DebounceReport($this->fakeReportData(['debounce' => ['did_you_mean' => '']]));
        $this->assertNull($report->correctedAddress());

        $report = new DebounceReport($this->fakeReportData(['debounce' => ['did_you_mean' => 'stash.yazan@gmail.com']]));
        $this->assertEquals('stash.yazan@gmail.com', $report->correctedAddress());
    }

    protected function fakeReportData($overrides = [])
    {
        return array_merge([
            "debounce" => [
                "email" => "stashyazan@gmail.com", //
                "code" => "5",
                "role" => "false", //
                "free_email" => "true", //
                "result" => "Safe to Send",
                "reason" => "Deliverable",
                "send_transactional" => "1", //
                "did_you_mean" => "" //
            ],
            "success" => "1",
            "balance" => "99"
        ], $overrides);
    }
}
