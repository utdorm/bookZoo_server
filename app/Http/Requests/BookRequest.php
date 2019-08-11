<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->url()) {
            case route('addBook'):
                return $this->addBookRules();
            case route('searchByTitle'):
                return $this->searchRules();
            case route('editBookTitle', ['id' => $this->route('id')]):
                return $this->editTitleRules();
            case route('editBookAuthor', ['id' => $this->route('id')]):
                return $this->editAuthorRules();
            case route('editBookSummary', ['id' => $this->route('id')]):
                return $this->editSummaryRules();
            case route('editTrendiness', ['id' => $this->route('id')]):
                return $this->editTrendinessRules();
            case route('editCondition', ['id' => $this->route('id')]):
                return $this->editConditionRules();
            case route('editPrice', ['id' => $this->route('id')]):
                return $this->editPrice();
            case route('addBookTags', ['id' => $this->route('id')]):
                return $this->addBookTags();
            case route('deleteBookTags', ['id' => $this->route('id')]): 
                return $this->deleteBookTags();
            default:
                return [];
        }
    }
    public function addBookRules(){
        return [
            'title' => 'string|required',
            'author' => 'string|required',
            'summary' => 'string|required',
            'isTrending' => 'boolean',
            'price' => 'numeric|required',
            'rentingPrice' => 'numeric|required',
            'condition' => 'in:Brand New,Good,Medium,Low'
        ];
    }
    public function searchRules(){
        return [
            'keyword' => 'required|string'
        ];
    }
    public function editTitleRules(){
        return [
            'title' => 'required|string'
        ];
    }
    public function editAuthorRules(){
        return [
            'author' => 'required|string'
        ];
    }
    public function editSummaryRules(){
        return [
            'summary' => 'required|string' 
        ];
    }
    public function editConditionRules(){
        return [
            'condition' => 'in:Brand New,Good,Medium,Low|required'
        ];
    }
    public function editTrendinessRules(){
        return [
            'isTrending' => 'in:yes,no|required',
            'isNewArrival' => 'in:yes,no|required'
        ];
    }
    public function editPrice(){
        return [
            'price' => 'numeric|required',
            'rentingPrice' => 'numeric|required'
        ];
    }
    public function addBookTags() {
        return [
            'tags' => 'required|array|min:1',
            'tags.*' => 'required'
        ];
    }

    public function deleteBookTags() {
        return [
            'tags' => 'required|array',
            'tags.*' => 'required'
        ];
    }
}
