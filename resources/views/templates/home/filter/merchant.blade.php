
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">merchant</h4>

    <div class="content">
        <div class="group">
            <input ng-model="filter.merchant" type="text" placeholder="merchant">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('merchant')" class="clear-search-button">clear</button>
        </span>
        </div>
    </div>

</div>