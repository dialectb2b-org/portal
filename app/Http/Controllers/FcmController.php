<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FcmController extends Controller
{
    public function storeToken(Request $request)
    {
        $validatedData = $request->validate([
            'fcm_token' => 'required|string', // You can add more validation rules if needed
        ]);

        // Assuming you have a "users" table, you can associate the FCM token with the authenticated user
        $user = auth()->user();
        $user->update(['fcm_token' => $validatedData['fcm_token']]);

        return response()->json(['message' => 'FCM token stored successfully'], 200);
    }

    public function sendPushNotification(Request $request)
    {
        

        $title = "Title";
        $body = "Body";
        $deviceToken = "fAh88VJqV6U6IBQReARub1:APA91bHB0A7ERgX8qKXbUdgyLhksSSjBpxbqwqUxziGNMBhF3w4xZa6s_HPCithXm1QhTAIwx8YqjCxZ0HXHE-GpVjeLyGhN86zjdYd5jzIgAWhDUosaCoBxXNDzCtpZTye0vcxZIuiL";

        // Replace this with your server key obtained from Firebase Console -> Project Settings -> Cloud Messaging
        $serverKey =  'AAAAhTIFZT8:APA91bFpUJGoe1OC7DSc_2PFNFjPLEFPEQ_jzD9LRVk1h_InaNzfqPWoFpKq3jb_fKr4Xd_T7UsWOEeGu6vWv5MQ9M53xtOzPTShby6XavSk2x7CFZfHf8I04Vk4v3sqiFF61s_Pqxrf';

        $headers = [
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ];

        $data = [
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'to' => $deviceToken,
        ];

        try {
            $response = Http::withHeaders($headers)->post('https://fcm.googleapis.com/fcm/send', $data);
            dd($response);
            if ($response->successful()) {
                return response()->json(['message' => 'Push notification sent successfully'], 200);
            } else {
                // Handle non-successful responses here
                return response()->json(['error' => 'Failed to send push notification'], 500);
            }
        } catch (\Exception $e) {
            // Handle exceptions here
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
