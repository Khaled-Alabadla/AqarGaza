<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

class WebsiteSettingsController extends Controller
{
    public function settings()
    {
        Artisan::call('cache:clear');

        Gate::authorize('website_settings.index');

        $settings = Setting::select('key', 'value')->get()->toArray();
        $new_settings = [];
        foreach ($settings as $setting) {
            $new_settings[$setting['key']] = $setting['value'];
        }
        $settings = $new_settings;
        return view('dashboard.settings', compact('settings'));
    }

    public function settings_save(Request $request)
    {
        Artisan::call('cache:clear');

        Gate::authorize('website_settings.update');

        $data = $request->except('site_logo', '_method', '_token');

        if ($request->hasFile('site_logo')) {
            $directory = 'logo';
            $file_name = time() . $request->file('site_logo')->getClientOriginalName();
            $site_logo = $request->file('site_logo')->storeAs($directory, $file_name, 'public_uploads');
            $data['site_logo'] = $site_logo;
        }
        foreach ($data as $key => $value) {
            Setting::updateOrCreate([
                'key' => $key
            ], [
                'value' => $value
            ]);
        }

        return redirect()->back()->with('success', 'تم الحفظ بنجاح');
    }

    public function delete_logo()
    {
        Artisan::call('cache:clear');

        Setting::where('key', 'site_logo')->update([
            'value' => null
        ]);
    }
}
