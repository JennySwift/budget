<template>
    <div id="transactions-page">
        <new-transaction></new-transaction>

        <div id="totals-transactions-filter-container">
            <totals></totals>

            <transactions></transactions>

            <transactions-filter></transactions-filter>
        </div>
    </div>

</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    import TransactionsComponent from './TransactionsComponent.vue'
    import NewTransactionComponent from '../../components/new_transaction/NewTransactionComponent.vue'
    import TotalsComponent from '../../components/TotalsComponent.vue'
    import FilterComponent from '../../components/filter/FilterComponent.vue'

    export default {
        data: function () {
            return {
                // page: 'home',
                shared: store.state,
                colors: {},
                hoveringTotalsButton: false
            };
        },
        components: {
            'transactions': TransactionsComponent,
            'new-transaction': NewTransactionComponent,
            'totals': TotalsComponent,
            'transactions-filter': FilterComponent
        },
        computed: {
            // tab: function () {
            //     return this.homePageRepository.tab;
            // }
        },
        methods: {

            /**
             *
             * @param tab
             */
            // switchTab: function (tab) {
            //     HomePageRepository.setTab(tab);
            //     FilterRepository.runFilter(this);
            // },

            /**
             *
             */
            respondToMouseEnterOnTotalsButton: function () {
                TotalsRepository.respondToMouseEnterOnTotalsButton(this);
            },

            /**
             *
             */
            respondToMouseLeaveOnTotalsButton: function () {
                TotalsRepository.respondToMouseLeaveOnTotalsButton(this);
            },

            /**
             *
             */
            toggleNewTransaction: function () {
                store.toggle('show.newTransaction');
            }
        },
        props: [],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../../../sass/variables';
    //#merchant-description-container {
    //    position: relative;
    //    #new-transaction-merchant-autocomplete, #new-transaction-description-autocomplete {
    //        max-height: 200px;
    //        overflow: scroll;
    //        z-index: 9;
    //        background: white;
    //        width: 508px;
    //        position: absolute;
    //        &#new-transaction-merchant-autocomplete {
    //            left: 0;
    //        }
    //        &#new-transaction-description-autocomplete {
    //            right: 0;
    //        }
    //        table {
    //            border-radius: 0;
    //            td, th {
    //                max-width: 57px;
    //                font-size: 13px;
    //                padding: 0;
    //                //overflow: scroll;
    //                border-radius: 0;
    //            }
    //            .selected {
    //                //            background-color: lightblue;
    //                color: #ffffff;
    //            }
    //        }
    //    }
    //}





    #totals-transactions-filter-container {
        display: flex;
        > * {
            margin: 0 10px;
        }

        #transactions {
            flex-grow: 1;
            table {
                width: 100%;
                background: white;
                border: 2px solid #ddd;
                //box-shadow: 3px 3px 10px #777;
                margin: auto;
                thead {
                    tr {
                        background: white;
                        color: $info;
                    }
                }
                tbody {
                    //        position: relative;
                }
                td {
                    height: 39px;
                    position: relative;
                    transition: all, 1.5s, ease;
                    &.description, &.merchant {
                        max-width: 120px;
                    }
                    &:hover {
                        .tooltip-container {
                            visibility: visible;
                        }
                    }
                    div:not(.tooltip) {
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap;
                    }
                    .tooltip-container {
                        visibility: hidden;
                        background: #777;
                        color: #fff;
                        border-radius: 4px;
                        position: absolute;
                        left: 90px;
                        z-index: 9;
                        padding: 9px 30px;
                        top: 0;
                        bottom: 0;
                        margin: auto;
                        .tooltip {

                        }
                    }
                }
                .tag-location-container {
                    height: 24px;
                    td {
                        padding: 9px 0px 2px 0px;
                        height: 24px;
                    }
                    .type {
                        font-size: 6px;
                    }
                }
                .results_inner_div {
                    td {
                        border-top: 1px solid #777;
                    }
                }
            }
        }
    }



    @media all and (max-width: 575px) {
        #transactions {
            td, th {
                max-width: 57px;
                font-size: 9px;
                padding: 0;
                overflow: scroll;
                .fa {
                    font-size: 9px;
                }
            }
        }
    }


</style>