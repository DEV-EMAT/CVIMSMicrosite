<?php

namespace App\Comprehensive;

use Illuminate\Database\Eloquent\Model;

class EventHasAttendance extends Model
{
    protected $connection = "comprehensive";

    protected $fillable = [
                            "attendances_id",
                            "attendees",
                            "person_code",
                            "user_id",
                            "attendee_status",
                            "attendee_remarks",
                            "created_at",
                            "updated_at",
                            "status",];

    protected $hidden = ["created_at", "updated_at"];
}
