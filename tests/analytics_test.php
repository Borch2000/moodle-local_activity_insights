<?php
// Basic PHPUnit test scaffold
class local_activity_insights_analytics_test extends \advanced_testcase {
    public function test_compute_score_range() {
        $this->resetAfterTest();
        $calc = new \local_activity_insights\analytics\riskcalculator();
        // Create a user
        $user = self::getDataGenerator()->create_user();
        $res = $calc->compute_score_for_user($user->id);
        $this->assertInternalType('array', $res);
        $this->assertTrue($res['score'] >= 0 && $res['score'] <= 1);
        $this->assertContains($res['level'], [1,2,3]);
    }
}
