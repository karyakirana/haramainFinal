@props(['modalId'=>'', 'formId'=>'', 'headerId'=>'', 'ukuran'=>''])
<div class="modal fade" id="{{$modalId}}" data-backdrop="static" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ ($ukuran == 'besar') ? 'modal-xl' : 'modal-lg' }}" role="document">
        <div class="modal-content">
            @if($headerId != '')
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            @endif
            <form id="{{$formId}}">
                <div class="modal-body">
                    {{$slot}}
                </div>
                <div class="modal-footer">
                    {{$footer}}
                </div>
            </form>
        </div>
    </div>
</div>
