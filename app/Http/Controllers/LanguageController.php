<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Language;
use App\Models\Translation;
use Cache;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_languages'])->only('index');
        $this->middleware(['permission:add_languages'])->only('create', 'store');
        $this->middleware(['permission:edit_languages'])->only('edit', 'update');
        $this->middleware(['permission:delete_languages'])->only('destroy');
    }

    public function changeLanguage(Request $request)
    {
    	$request->session()->put('locale', $request->locale);
        $language = Language::where('code', $request->locale)->first();
    	flash(translate('Language changed to ').$language->name)->success();
    }

    public function index(Request $request)
    {
        $languages = Language::paginate(10);
        return view('backend.settings.languages.index', compact('languages'));
    }

    public function create(Request $request)
    {
        return view('backend.settings.languages.create');
    }

    public function store(Request $request)
    {
        $language = new Language;
        $language->name = $request->name;
        $language->code = $request->code;
        $language->flag = $request->flag;
        $language->save();   
        flash(translate('Language has been inserted successfully'))->success();
        return redirect()->route('languages.index');
    }

    public function show(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'));
        if ($request->has('search')){
            $sort_search = $request->search;
            $lang_keys = $lang_keys->where('lang_key', 'like', '%'.$sort_search.'%');
        }
        $lang_keys = $lang_keys->paginate(50);
        return view('backend.settings.languages.language_view', compact('language','lang_keys','sort_search'));
    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);
        return view('backend.settings.languages.edit', compact('language'));
    }

    public function update(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE') == $language->code) {
            flash(translate('Default language can not be edited'))->error();
            return back();
        }
        $language->name = $request->name;
        $language->code = $request->code;
        $language->flag = $request->flag; 
        $language->save();
        
        flash(translate('Language has been updated successfully'))->success();
        return redirect()->route('languages.index');
    }

    public function key_value_store(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            $translation_def = Translation::where('lang_key', $key)->where('lang', $language->code)->latest()->first();
            if($translation_def == null){
                $translation_def = new Translation;
                $translation_def->lang = $language->code;
                $translation_def->lang_key = $key;
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
            else {
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
        }
        Cache::forget('translations-'.$language->code);
        flash(translate('Translations updated for ').$language->name)->success();
        return back();
    }

    public function update_rtl_status(Request $request)
    {
        $language = Language::findOrFail($request->id);
        $language->rtl = $request->status;
        if($language->save()){
            flash(translate('RTL status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE') == $language->code) {
            flash(translate('Default language can not be deleted'))->error();
        }
        else {
            if($language->code == Session::get('locale')){
                Session::put('locale', env('DEFAULT_LANGUAGE'));
            }
            Language::destroy($id);
            flash(translate('Language has been deleted successfully'))->success();
        }
        return redirect()->route('languages.index');
    }
}
