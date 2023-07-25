<?php
namespace DTApi\Helpers;

use Carbon\Carbon;
use DTApi\Models\Job;
use DTApi\Models\User;
use DTApi\Models\Language;
use DTApi\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeHelper
{
    public static function fetchLanguageFromJobId($id)
    {
        $language = Language::findOrFail($id);
        return $language1 = $language->language;
    }

    public static function getUsermeta($user_id, $key = false)
    {
        return $user = UserMeta::where('user_id', $user_id)->first()->$key;
        if (!$key)
            return $user->usermeta()->get()->all();
        else {
            $meta = $user->usermeta()->where('key', '=', $key)->get()->first();
            if ($meta)
                return $meta->value;
            else return '';
        }
    }

    public static function convertJobIdsInObjs($jobs_ids)
    {

        $jobs = array();
        foreach ($jobs_ids as $job_obj) {
            $jobs[] = Job::findOrFail($job_obj->id);
        }
        return $jobs;
    }

    public static function willExpireAt($due_time, $created_at)
    {
        $due_time = Carbon::parse($due_time);
        $created_at = Carbon::parse($created_at);

        $difference = $due_time->diffInHours($created_at);


        if($difference <= 90)
            $time = $due_time;
        elseif ($difference <= 24) {
            $time = $created_at->addMinutes(90);
        } elseif ($difference > 24 && $difference <= 72) {
            $time = $created_at->addHours(16);
        } else {
            $time = $due_time->subHours(48);
        }

        return $time->format('Y-m-d H:i:s');

    }

    // Test cases for 'willExpireAt' function, we can use PHPUnit library for unit testing as well
    public static function testWillExpireAt()
    {
        // Test case 1 - Difference less than or equal to 90 hours
        $due_time = '2023-07-24 12:00:00';
        $created_at = '2023-07-24 10:00:00';
        $expected = '2023-07-24 12:00:00';

        $result = willExpireAt($due_time, $created_at);
        echo ($result === $expected) ? "Test case 1 passed\n" : "Test case 1 failed\n";

        // Test case 2 - Difference between 24 and 72 hours
        $due_time = '2023-07-24 12:00:00';
        $created_at = '2023-07-23 10:00:00';
        $expected = '2023-07-24 02:00:00';

        $result = willExpireAt($due_time, $created_at);
        echo ($result === $expected) ? "Test case 2 passed\n" : "Test case 2 failed\n";

        // Test case 3 - Difference greater than 72 hours
        $due_time = '2023-07-27 12:00:00';
        $created_at = '2023-07-23 10:00:00';
        $expected = '2023-07-25 12:00:00';

        $result = willExpireAt($due_time, $created_at);
        echo ($result === $expected) ? "Test case 3 passed\n" : "Test case 3 failed\n";
    }

}

