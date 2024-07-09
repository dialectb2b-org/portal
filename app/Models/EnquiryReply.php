<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryReply extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function sender(){
        return $this->belongsTo(CompanyUser::class,'from_id','id');
    }

    public function enquiry(){
        return $this->belongsTo(Enquiry::class,'enquiry_id','id');
    }

    public function attachments(){
        return $this->hasMany(EnquiryAttachment::class,'reply_id','id');
    }

    public function relation(){
        return $this->belongsTo(EnquiryRelation::class,'enquiry_relation_id','id');
    }
    
    public function getShortContentAttribute()
    {
        return $this->shortBody($this->body, 100);
    }
    
    public function getStatusTextAttribute()
    {
        return $this->statusText($this->status, $this->is_read, $this->is_selected, $this->enquiry->shared, $this->is_recommanded);
    }

    public function getStatusColorAttribute()
    {
        return $this->statusColor($this->status);
    }

    private function shortBody($content, $limit = 100, $stripTags = true, $ellipsis = true) 
    {
        if ($content && $limit) {
            $content  = ($stripTags ? strip_tags($content) : $content);
            $ellipsis = ($ellipsis ? "..." : $ellipsis);
            $content  = mb_strimwidth($content, 0, $limit, $ellipsis);
            $content  = preg_replace('/(&nbsp;|\s)+/', ' ', $content); 
        }
        return $content;
    }
    
    private function statusText($status, $is_read, $is_selected, $shared_to, $is_recommanded)
    {
       
        if ($status == 0 && $is_read == 0) {
            if ($is_recommanded == 1 && $shared_to) {
                return 'Recommanded';
            } else {
                return 'Unread';
            }
        } else if ($status == 0 && $is_read == 1) {
            if ($is_recommanded == 1 && $shared_to) {
                return 'Recommanded';
            } else {
                return 'TBD';
            }
        } else if ($status == 1) {
            if ($is_selected == 1 && $shared_to) {
                return 'Selected';
            } else {
                return 'Shortlisted';
            }
        } else if ($status == 2) {
            if ($is_recommanded == 1 && $shared_to) {
                return 'Recommanded';
            } else {
                return 'On Hold';
            }
        } else if ($status == 3) {
            return 'Proceed';
        }
    }

    private function statusColor($status)
    {
        if ($status == 0) {
            return 'yellow';
        } else if ($status == 1) {
            return 'green';
        } else if ($status == 2) {
            return 'red';
        } else if ($status == 3) {
            return 'red';
        }
    }

    

}
