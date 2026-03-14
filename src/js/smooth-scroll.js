import smoothScroll from 'smooth-scroll';

export function smooth_scroll() {
  let options = {
    speed: 150,
    easing: 'easeOutCubic',
    offset: 0
    // offset: function () { return (window.innerWidth > 809) ? 0 : 50; },
  };
  new smoothScroll('a[href*="#"]', options);
}

