@props(['linkText'=>'#'])
<a href="{{$linkText}}" {{$attributes->merge(['class' =>'btn font-weight-bolder' ])}}>
    {{$slot}}
</a>
