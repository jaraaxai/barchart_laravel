<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quotes;
use Redirect, Response;


class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $quotes = Quotes::all();

        return view('quote.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $keyword = request('keyword');
        if( $keyword ){
          $quotes = Quotes::searchOne($keyword);

          if( $quotes ){
            return redirect()->route('quotes.edit', $quotes->id);
          } else {
            //return redirect('/create')->with('keyword', $keyword);
           return redirect()->route('quotes.create', $keyword);
          }
        } else {
          return redirect('/')->withErrors(['Insert symbol name!']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($keyword)
    {
        $title = "The given symbol does not exist";
        return view('quote.create', compact('keyword', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'symbol' => 'required|unique:quotes|max:50',
            'name' => 'required',
            'last_price' => 'numeric',
            'prichange' => 'numeric',
            'pctchange' => 'numeric',
            'volume' => 'numeric',
        ]);
        $quote = new Quotes;
        $quote->symbol = $request->symbol;
        $quote->name = $request->name;
        $quote->last_price = $request->last_price;
        $quote->prichange = $request->prichange;
        $quote->pctchange = $request->pctchange;
        $quote->volume = $request->volume;
        $quote->tradetime = date('Y-m-d h:i:s', time());
        $quote->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quote = Quotes::find($id);
        $title = "This symbol has already been added to the watchlist";
        return view('quote.create', compact('quote', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'last_price' => 'numeric',
            'prichange' => 'numeric',
            'pctchange' => 'numeric',
            'volume' => 'numeric',
        ]);
        $quote = Quotes::find($id);
        $quote->name = $request->name;
        $quote->last_price = $request->last_price;
        $quote->prichange = $request->prichange;
        $quote->pctchange = $request->pctchange;
        $quote->volume = $request->volume;
        $quote->tradetime = date('Y-m-d h:i:s', time());
        $quote->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quote = Quotes::find($id);
        $quote->delete();
        return Response::json(array(
                    'success' => true
                )); 
    }
}
