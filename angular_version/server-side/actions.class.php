<?php

/**
 * quotes actions.
 *
 * @package    next
 * @subpackage quotes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class quotesActions extends sfActions
{
  // ******* APIs ********
  protected function selectData(){
    $quotess = Doctrine::getTable('Quotes')
      ->createQuery('a')
      ->execute();

    $arr = [];
    foreach ($quotess as $key => $value) {
      $a = array();
      $a['id'] = $value->getId();
      $a['symbol'] = $value->getSymbol();
      $a['name'] = $value->getName();
      $a['last_price'] = $value->getLastPrice();
      if( $value->getPrichange() == 0 )
        $a['prichange'] = "unch";
      else
        $a['prichange'] = $value->getPrichange();

      if( $value->getPctchange() == 0 )
        $a['pctchange'] = "unch";
      else
        $a['pctchange'] = $value->getPctchange();

      $a['volume'] = $value->getVolume();
      $a['tradetime'] = date('m/d/Y', strtotime($value->getTradetime()));

      $arr[$key] = $a;
    }

    return $arr;
  }

  public function executeList(sfWebRequest $request)
  {
    $data = $this->selectData();

    return $this->renderText(json_encode($data, JSON_NUMERIC_CHECK));
  }

  public function executeAdd(sfWebRequest $request)
  {
    $errors     = array();    // array to hold validation errors
    $data       = array();    // array to pass back data
    // validate the variables ======================================================
      $id = $request->getParameter('id');
      $symbol = $request->getParameter('symbol');
      if (!isset($symbol)){
        $errors['symbol'] = 'Symbol is required.';
      }
      else{
        $quote = Doctrine::getTable('Quotes')->getSymbolByKey($symbol);
        if($id == 0 && $quote){
          $errors['symbol'] = 'This symbol has already been added to the watchlist!';
        }
      }
      $name = $request->getParameter('name');
      if (!isset($name))
        $errors['name'] = 'Name is required.';

      $last_price = $request->getParameter('last_price') ? $request->getParameter('last_price') : 0;
      if (!is_numeric($last_price))
        $errors['price'] = 'Symbol is required.';

      $prichange = $request->getParameter('prichange') ? $request->getParameter('prichange') : 0;
      if (!is_numeric($prichange))
        $errors['prichange'] = 'Change must be number.';

      $pctchange = $request->getParameter('pctchange') ? $request->getParameter('pctchange') : 0;
      if (!is_numeric($pctchange))
        $errors['pctchange'] = 'Change must be number.';

      $volume = $request->getParameter('volume');
      if (!isset($volume))
        $errors['volume'] = 'Volume is required.';
      elseif (!is_numeric($volume))
        $errors['volume'] = 'Volume must be number.';
    // return a response ===========================================================
      // response if there are errors
      if (count($errors) > 0) {
        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;
      } else {
        // if there are no errors, return a message
        $quote = Doctrine::getTable('Quotes')->find($id);
        if(!$quote){
          $quote = new Quotes();
        }
        $quote->setSymbol($symbol);
        $quote->setName($name);
        $quote->setLastPrice($last_price);
        $quote->setPrichange($prichange);
        $quote->setPctchange($pctchange);
        $quote->setVolume($volume);
        $quote->setTradetime(date('Y-m-d H:i:s', time()));
        $quote->save();

        $data['success'] = true;
        $data['names'] = $this->selectData();
      }
      // return all our data to an AJAX call
      return $this->renderText(json_encode($data, JSON_NUMERIC_CHECK));
  }

  public function executeApisearch(sfWebRequest $request){
    
    $data       = array();    // array to pass back data
    $params = json_decode($request->getContent(), true);
    
    $keyword = $params['keyword'];
    
    if( $keyword ){
      $this->quotes = Doctrine::getTable('Quotes')->getSymbolByKey($keyword);

      if( $this->quotes ){
        $data['success'] = true;
        $data['message'] = 'This symbol has already been added to the watchlist!';
        $value = $this->quotes;
        $a = array();
        $a['id'] = $value->getId();
        $a['symbol'] = $value->getSymbol();
        $a['name'] = $value->getName();
        $a['last_price'] = $value->getLastPrice();
        $a['prichange'] = $value->getPrichange();
        $a['pctchange'] = $value->getPctchange();
        $a['volume'] = $value->getVolume();
        $a['tradetime'] = date('m/d/Y', strtotime($value->getTradetime()));
        $data['quote'] = $a;
      } else {
        $data['success'] = false;
        $data['message'] = 'The given symbol does not exist!';

      }
    }else{
      $data['success'] = false;
    }
    
    return $this->renderText(json_encode($data, JSON_NUMERIC_CHECK));
  }

}
