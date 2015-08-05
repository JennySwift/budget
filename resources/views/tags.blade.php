@extends('layouts.master')

@section('controller', 'TagsController')

@section('page-content')
    @include('templates.popups.settings.index')

    <div id="tags">

        <input ng-keyup="insertTag($event.keyCode)" type="text" class="font-size-sm center margin-bottom" id="new-tag-input" placeholder="new tag">

        <table class="table table-bordered">
            <tr ng-repeat="tag in tags">
                <td>[[tag.name]]</td>
                <td>
                    <button ng-click="showEditTagPopup(tag.id, tag.name)">edit</button>
                </td>
                <td>
                    <button ng-click="deleteTag(tag.id)" class="btn btn-default">delete</button>
                </td>
            </tr>
        </table>
    </div>

@stop