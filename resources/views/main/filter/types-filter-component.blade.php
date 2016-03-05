<script id="types-filter-template" type="x-template" xmlns:v-on="http://www.w3.org/1999/xhtml"
        xmlns:v-bind="http://www.w3.org/1999/xhtml">

    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">types</h4>

        <div class="types transition content">

            <div class="checkbox-container">
                <input type="checkbox" id="filter-in-all-types">
                <label for="filter-in-all-types">all</label>
            </div>

            <div class="checkbox-container">
                <input id="filter-in-no-types" type="checkbox">
                <label for="filter-in-no-types">none</label>
            </div>

            <div
                v-show="filterTab === 'show'"
                v-if="filter.types"
                v-for="type in types"
                class="checkbox-container"
            >

                <input
                        v-model="filter.types.in"
                        v-on:change="runFilter()"
                        :id="type"
                        :value="type"
                        :disabled="filter.types.out.indexOf(type) !== -1"
                        type="checkbox"
                >
                <label
                    :for="type"
                    v-bind:class="{'disabled': filter.types.out.indexOf(type) !== -1}"
                >
                    @{{type}}
                </label>

            </div>

            <div
                v-show="filterTab === 'hide'"
                v-if="filter.types"
                v-for="type in types"
                class="checkbox-container"
            >
                <input
                        v-model="filter.types.out"
                        v-on:change="runFilter()"
                        :id="type + '-out'"
                        :value="type"
                        :disabled="filter.types.in.indexOf(type) !== -1"
                        type="checkbox"
                >
                <label
                        :for="type + '-out'"
                        v-bind:class="{'disabled': filter.types.in.indexOf(type) !== -1}"
                >
                    @{{type}}
                </label>

            </div>

        </div>

    </div>

</script>