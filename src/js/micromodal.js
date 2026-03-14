import MicroModal from 'micromodal';

export function modal_footer() {
  // console.log('micromodal');
  MicroModal.init({
    openClass: 'is-open',
    disableScroll: true,
    disableFocus: true, // focus誤作動対策
  });
  // デフォルト表示の場合
  // MicroModal.show('modal-1');
}