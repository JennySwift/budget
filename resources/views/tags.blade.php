@extends('layouts.master')

@section('controller', 'TagsController')

@section('page-content')
    @include('templates.popups.settings.index')

    <div id="tags">

        <div class="create-new-tag">
            <label>Create a new tag</label>

            <div class="flex">

                <input
                    ng-keyup="insertTag($event.keyCode)"
                    type="text"
                    class="font-size-sm center margin-bottom"
                    id="new-tag-input"
                    placeholder="new tag">

                <div>
                    <button class="btn btn-success">Create</button>
                </div>

            </div>




        </div>


        <table class="">
            <tr ng-repeat="tag in tags">

                <td>
                    <span
                        ng-click="showEditTagPopup(tag.id, tag.name)"
                        ng-class="{'tag-with-fixed-budget': tag.fixed_budget !== null, 'tag-with-flex-budget': tag.flex_budget !== null, 'tag-without-budget': tag.fixed_budget === null || tag.flex_budget === null}"
                        class="label label-default pointer">
                        [[tag.name]]
                    </span>
                </td>

                <td>
                    <button ng-click="deleteTag(tag.id)" class="btn btn-danger btn-sm">delete</button>
                </td>

            </tr>
        </table>
    </div>

@stop