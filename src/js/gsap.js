// CDN経由でgsapを読み込むため、インポートを削除
// import { gsap } from 'gsap';
// import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { mediaQuerySm,mediaQueryMd,mediaQueryLg } from './media-query';

// CDN利用によるリントエラー対策として グローバル変数を宣言
/* global gsap */
/* global ScrollTrigger */

// 必要なプラグインのみを登録
gsap.registerPlugin(ScrollTrigger);


/**
 * topからのスクロールでクラス追加。リサイズ対応で関数化
 * @param {*} elemTarget
 * @param {*} classNameAdd
 */
export function fixedButton(elemTarget){

  const eventList = {
    'enter' : function () {
      // console.log('enter');
      gsap.to(elemTarget, {
        autoAlpha: 1,
        duration: .2,
      });
    },
    'leave' : function () {
      // console.log('leave');
      gsap.to(elemTarget, {
        autoAlpha: 0,
        duration: .2,
      });
    }
  };

  const gsapButton = (mediaQuery) => {
    if (mediaQuery.matches) {
      ScrollTrigger.create({
        trigger: document.body,
        start: '300px top',
        end: document.body.innerHeight,
        onEnter: function () { eventList.enter(); },
        onLeaveBack: function () { eventList.leave(); },
        onLeave: function () { eventList.leave(); },
        onEnterBack: function () { eventList.enter(); }
      });
    }
  };

  if (document.querySelector(elemTarget)) {
    //リサイズ対応
    mediaQueryMd.addEventListener('change', gsapButton);
    // 初期化
    gsapButton(mediaQueryMd);
  }

}


/**
 * コンテンツフェードイン
 * ロード時の誤動作対策のため.js-fadeup, .js-fadeup__once にはcssで opacity: 0;
 */
export function fadeUpContent(){
  document.querySelectorAll('.js-fadeup').forEach((elem) => {
    gsap.fromTo(elem,
      {
        autoAlpha: 0,
        y:30,
      },
      {
        autoAlpha: 1,
        y:0,
        duration: .4,
        ease: 'power1.out',
        scrollTrigger: {
          trigger: elem,
          start: 'top bottom-=20%' ,
          toggleActions: 'restart none none reverse',
        }
      }
    );
  });
  document.querySelectorAll('.js-fadeup__once').forEach((elem) => {
    gsap.fromTo(elem,
      {
        autoAlpha: 0,
        y:30,
      },
      {
        autoAlpha: 1,
        y:0,
        duration: .4,
        ease: 'power1.out',
        scrollTrigger: {
          trigger: elem,
          start: 'top bottom-=20%' ,
        }
      }
    );
  });
}


/**
 * 時間差 フェードイン for ulタグ
 * ロード時の誤動作対策のため..js-fadeup__stagger--list を追加して liに opacity: 0; セット
 */
export function staggerFadeupForList(elemUlClass, itemCount){
  document.querySelectorAll(elemUlClass).forEach((elemUl) => {
    const target = elemUl.querySelectorAll('li');
    gsap.fromTo(target,
      {
        autoAlpha: 0,
        y: 30,
      },
      {
        autoAlpha: 1,
        y: 0,
        duration: .4,
        ease: 'power1.out',
        scrollTrigger: {
          trigger: elemUl,
          start: 'top bottom-=20%',
          // toggleActions: 'restart none none reverse',
        },
        stagger: {
          from: 'start',
          amount: itemCount * 0.1,
        },
      }
    );
  });
}

// 以下作例



/**
 * front スタッフ用
 * transformでレイアウトをずらしているのでfromToで動かすと不具合
 */
export function staggerFadeupForFrontStaffList(elemUlClass, itemCount){
  const target = elemUlClass + ' li';
  gsap.from(target ,
    {
      autoAlpha: 0,
      y: 50,
      duration: .4,
      ease: 'power1.out',
      scrollTrigger: {
        trigger: elemUlClass,
        start: 'top bottom-=35%' ,
      // toggleActions: 'restart none none reverse',
      },
      stagger: {
        from: 'start',
        amount: itemCount * 0.1,
      },
    });
}



/**
 * スクラブギミック for front
 * @param {*} elemTarget
 * @param {*} elemTrigger
 */
export function scrubForFrontPic(elemTarget, elemTrigger){
  const gsapRact = (mediaQuery) => {
    if (mediaQuery.matches) {
      gsap.to(elemTarget,
        {
          width:0,
          ease: 'power1.out',
          scrollTrigger: {
            trigger: elemTrigger,
            start: 'top bottom-=35%' ,
            end: 'bottom bottom+=5%',
            scrub: 1.8,

          }
        });

    } else {
      gsap.to(elemTarget,
        {
          width:0,
          ease: 'power1.out',
          scrollTrigger: {
            trigger: elemTrigger,
            start: 'top bottom-=100px' ,
            end: 'bottom bottom+=15%',
            scrub: 2,
            // markers: true
          }
        });
    }
  };

  if (document.querySelector(elemTarget)) {
    //リサイズ対応
    mediaQueryMd.addEventListener('change', gsapRact);
    // 初期化
    gsapRact(mediaQueryMd);
  }
}



/**
 * gsap for headline
 * @param {*} elemTarget
 * @param {*} elemTrigger
 */
export function ractForHeadlinePic(elemAlphabet,elemTarget, elemTargetParent){
  gsap
    .timeline({ defaults: {duration: .2, ease: 'power1.out'} })
    .set([elemAlphabet],
      {
        autoAlpha: 0,
      })
    .to(elemAlphabet, {
      autoAlpha: 1,
      duration: .4
    })
    .to(elemTargetParent, {
      'clip-path': 'inset(0% 0% 0% 0%)',
    })
    .to(elemTarget, {
      width:0,
      delay: .4
    });
}




/**
 * 四角形を組合せた表示演出
 */
export function effectRect(){
  document.querySelectorAll('.js-effectRect__container').forEach((elem) => {
    // gsap.from(elem,
    //   {
    //     autoAlpha: 0,
    //     duration: 0.4,
    //     scrollTrigger: {
    //       trigger: elem,
    //       start: 'top bottom-=25%' ,
    //       toggleActions: 'restart none none reverse',
    //     }
    //   });
    gsap.fromTo(elem, {
      'clip-path': 'inset(0% 100% 0% 0%)',
    },
    { 'clip-path': 'inset(0% 0% 0% 0%)',
      duration: 0.3,
      scrollTrigger: {
        trigger: elem,
        start: 'top bottom-=25%' ,
        toggleActions: 'restart none none reverse',
      }
    });

  });
  document.querySelectorAll('.js-effectRect__rect').forEach((elem) => {
    gsap.to(elem,
      {
        width: 0,
        duration: 0.2,
        delay: 0.5,
        ease: 'power4.out',
        scrollTrigger: {
          trigger: elem,
          start: 'top bottom-=25%' ,
          toggleActions: 'restart none none reverse',
        }
      }
    );
  });
}


/**
 * クリップパス表示演出
 */
export function effectClipPath(elemTarget, elemTrigger){
  const elem = document.querySelector(elemTarget);
  gsap.fromTo(elem, {
    // chromeでインライン要素に対して clippath の高さがうまく取れないので対策
    'clip-path': 'inset(0% 100% -500% 0%)',
  },
  { 'clip-path': 'inset(0% 0% -500% 0%)',
    duration: 0.4,
    scrollTrigger: {
      trigger: elemTrigger,
      start: 'top center+=12%' ,
      end: 'top center-=12%' ,
      toggleActions: 'restart none none reverse',
    }
  });
}





/**
 * マーカー演出
 */
export function markerAdvantage(){
  const elem = document.querySelector('.p-advantage__message');
  gsap.fromTo(elem, {
    backgroundSize:'0% 100%',
  },
  { backgroundSize:'100% 100%',
    duration: 0.6,
    ease: 'power4.out',
    scrollTrigger: {
      trigger: '.p-advantage__message',
      start: 'top bottom-=20%' ,
      toggleActions: 'restart none none reverse',
    }
  });
}




export function thumbnailInstagram(){
  gsap.from('.sbi_photo_wrap', {
    y: 50,
    autoAlpha: 0,
    duration: 0.4,
    scrollTrigger: {
      trigger: '.p-instagram__feed',
      start: 'top bottom-=20%' ,
      // toggleActions: 'restart none none reverse',
    },
    stagger: {
      from: 'start',
      amount: 0.5,
    },
  });
}

