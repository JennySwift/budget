
@include('templates.shared.real-footer')

<script type="text/javascript" src="/js/all.js"></script>

{{--<script type="text/javascript" src="/js/plugins.js"></script>--}}
{{--<script type="text/javascript" src="/js/app.js"></script>--}}

@include('directive-templates.feedback')
@include('directive-templates.totals')
@include('directive-templates.tag-autocomplete')
@include('directive-templates.transaction-autocomplete')
@include('directive-templates.checkboxes')
@include('directive-templates.filter.accounts')
@include('directive-templates.filter.date')
@include('directive-templates.filter.tags')
@include('directive-templates.filter.total')

{{--<script type="text/javascript" src="/js/filters.js"></script>--}}
{{--<script type="text/javascript" src="/js/controllers.js"></script>--}}
{{--<script type="text/javascript" src="/js/factories.js"></script>--}}
{{--<script type="text/javascript" src="/js/directives.js"></script>--}}

</body>
</html>
