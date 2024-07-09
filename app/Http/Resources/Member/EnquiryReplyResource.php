<?php

namespace App\Http\Resources\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EnquiryReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'enquiry_id' => $this->enquiry_id,
            'sender' => $this->sender,
            'sender_company' => $this->sender->company,
            'short_body' => $this->shortBody($this->body),
            'body' => $this->body,
            'created_at' => Carbon::parse($this->created_at)->format('d F, Y H:i:s A'),
            'created_time' => Carbon::parse($this->created_at)->diffForHumans(),
            'status' => $this->status,
            'status_text' => $this->status_text($this->status,$this->is_read,$this->is_selected,$this->enquiry->shared_to,$this->is_recommanded),
            'status_color' => $this->status_color($this->status),
            'hold_reason' => $this->hold_reason,
            'suggested_remarks' => $this->suggested_remarks,
            'is_selected' => $this->is_selected,
            'is_recommanded' => $this->is_recommanded,
            'is_ignored' => $this->is_ignored,
            'is_interested' => $this->is_interested,
            'participation_approved' => $this->participation_approved,
            'attachments' => $this->attachments,
            'enquiry' => $this->enquiry, 
            'relation' => $this->relation   
        ];
    }

    function shortBody($content, $limit = 200, $stripTags = true, $ellipsis = true) 
    {
        if ($content && $limit) {
            $content  = ($stripTags ? strip_tags($content) : $content);
            $ellipsis = ($ellipsis ? "..." : $ellipsis);
            $content  = mb_strimwidth($content, 0, $limit, $ellipsis);
            $content  = preg_replace('/(&nbsp;|\s)+/', ' ', $content); // Remove white spaces and &nbsp; entities
        }
        return $content;
    }

    public function status_text($status,$is_read,$is_selected,$shared_to,$is_recommanded){
        if($status == 0 && $is_read == 0){
            if($is_recommanded == 1 && $shared_to !== null){
                return 'Recommanded';
            }
            else{
                return 'Unread';
            }
        }
        else if($status == 0 && $is_read == 1){
             if($is_recommanded == 1 && $shared_to !== null){
                  return 'Recommanded';
             }
             else{
                  return 'TBD'; 
             }
        }
        else if($status == 1 ){
            
            if($is_selected == 1 && $shared_to !== null){
                return 'Selected';
            }
            else{
                return 'Shortlisted';
            }  
        }
        else if($status == 2){
            if($is_recommanded == 1 && $shared_to !== null){
                  return 'Recommanded';
             }
             else{
                  return 'On Hold';
             }
        }
        else if($status == 3){
            return 'Proceed';
        }
    }

    public function status_color($status){
        if($status == 0){
            return 'yellow';
        }
        else if($status == 1){
            return 'green';
        }
        else if($status == 2){
            return 'red';
        }
        else if($status == 3){
            return 'red';
        }
    }
}
