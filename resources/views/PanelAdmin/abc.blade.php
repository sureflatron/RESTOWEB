@extends('Layouts.Panel')
@section('Contenido')
<style>
    @import url(http://fonts.googleapis.com/css?family=Lato:300);
    #avengers {
        font-size: 16px;
        position: absolute;
        width: 65%;
        font-family: 'Lato', sans-serif;
    }

    .wrappera {
        position: relative;
        width: 550px;
        height: 365px;
        margin: 15% auto;
        -webkit-animation: shake 7s infinite;
        animation: shake 7s infinite;
    }

    .hulk {
        position: absolute;
        margin-left: 200px;
        bottom: 0px;
        margin-bottom: 300px;
    }
    .hulk .head {
        width: 4.713em;
        position: absolute;
        margin-top: -2.75em;
        margin-left: 3.563em;
        border-bottom: 3.75em solid #bdbd00;
        border-left: 0.938em solid transparent;
        border-right: 0.938em solid transparent;
        height: 0;
        z-index: 10;
    }
    .hulk .head::after {
        content: "";
        width: 2.25em;
        height: 0.375em;
        position: absolute;
        margin-top: 0.538em;
        margin-left: 0.313em;
        background: #034600;
    }
    .hulk .head::before {
        content: "";
        width: 3.25em;
        height: 0.75em;
        position: absolute;
        margin-top: -0.75em;
        margin-left: -0.25em;
        background: #034600;
        border-top-left-radius: 1.875em;
        border-top-right-radius: 1.875em;
    }
    .hulk .mouth {
        width: 31px;
        height: 0px;
        position: absolute;
        margin-top: 25px;
        margin-left: 8px;
        overflow: hidden;
        background-image: -webkit-linear-gradient(top, #ffffff, #ffffff 25%, #4b0a00 25%, #4b0a00 70%, #ffffff 75%, #ffffff 100%);
        background-image: linear-gradient(to bottom, #ffffff, #ffffff 25%, #4b0a00 25%, #4b0a00 70%, #ffffff 75%, #ffffff 100%);
        border-top-left-radius: 0.5em;
        border-top-right-radius: 0.5em;
        -webkit-animation: mouthOpen 7s infinite;
        animation: mouthOpen 7s infinite;
    }
    .hulk .mouth::before {
        content: "";
        width: 10px;
        height: 7px;
        position: absolute;
        background: #902e2b;
        margin-top: 9px;
        margin-left: 11px;
        border-top-left-radius: 0.5em;
        border-top-right-radius: 0.5em;
    }
    .hulk .right-arm {
        width: 13.313em;
        height: 13.75em;
        margin-top: 1em;
        margin-left: 2em;
        border-radius: 50%;
        background: #b5b500;
        position: absolute;
        clip: rect(-1em, 11.2em, 12.55em, 7.625em);
        -webkit-animation: hulkRightarm 7s infinite;
        animation: hulkRightarm 7s infinite;
    }
    .hulk .right-arm::after {
        content: "";
        width: 12.063em;
        height: 9.625em;
        border-radius: 50%;
        background: white;
        position: absolute;
        -webkit-transform: translateY(1.25em);
        transform: translateY(1.25em);
        margin-left: -3.313em;
    }
    .hulk .left-arm {
        width: 13.313em;
        height: 13.75em;
        margin-top: 1em;
        margin-left: -4.3em;
        border-radius: 50%;
        background: #949400;
        position: absolute;
        clip: rect(-0.188em, 6.1em, 13.875em, 2.3em);
        -webkit-animation: hulkLeftarm 7s infinite;
        animation: hulkLeftarm 7s infinite;
    }
    .hulk .left-arm::after {
        content: "";
        width: 12.063em;
        height: 9.625em;
        border-radius: 50%;
        background: white;
        position: absolute;
        -webkit-transform: translateY(1.25em);
        transform: translateY(1.25em);
        margin-left: 4.563em;
    }
    .hulk .fist {
        width: 4.375em;
        height: 4.375em;
        position: absolute;
        border-radius: 50%;
        margin-top: 11.7em;
        margin-left: -2.1em;
        background: #949400;
        box-shadow: 11.1em 0 0 #b5b500;
        z-index: 10;
        -webkit-animation: hulkHands 7s infinite;
        animation: hulkHands 7s infinite;
    }
    .hulk .body {
        width: 12.5em;
        height: 12.5em;
        background: #bdbd00;
        border-radius: 50%;
        position: absolute;
        box-shadow: inset 1.125em 0 0 #a4a400, -0.938em 0 0 #8a8a00;
        z-index: 10;
    }
    .hulk .body::before {
        content: "";
        width: 0.75em;
        height: 0.75em;
        border-radius: 50%;
        position: absolute;
        margin-top: 3.125em;
        margin-left: 1.375em;
        background: #8a8a00;
        box-shadow: 7.625em 0 0 #8a8a00;
    }
    .hulk .body::after {
        content: "";
        position: absolute;
        width: 0.313em;
        height: 0.313em;
        border-radius: 50%;
        margin-top: 10em;
        margin-left: 5.625em;
        background: #8a8a00;
        box-shadow: 0 2.625em 0 #9ac7f1;
    }
    .hulk .right-leg {
        width: 0.625em;
        height: 6.438em;
        position: absolute;
        margin-left: 4.25em;
        margin-top: 12.3em;
        background-image: -webkit-linear-gradient(top, #2e0e62, #2e0e62 50%, #575700 50%, #575700 100%);
        background-image: linear-gradient(to bottom, #2e0e62, #2e0e62 50%, #575700 50%, #575700 100%);
        -webkit-animation: hulkPants2 7s infinite;
        animation: hulkPants2 7s infinite;
    }
    .hulk .right-leg::after {
        content: "";
        width: 0.938em;
        height: 0.875em;
        position: absolute;
        margin-left: 0.625em;
        background: #43158e;
    }
    .hulk .right-leg::before {
        content: "";
        width: 1.125em;
        height: 0.188em;
        position: absolute;
        margin-top: 6.25em;
        margin-left: -1.125em;
        background: #575700;
    }
    .hulk .left-leg {
        width: 0.625em;
        height: 6.438em;
        position: absolute;
        margin-left: 6.688em;
        margin-top: 12.3em;
        background-image: -webkit-linear-gradient(top, #581bbb, #581bbb 50%, #bdbd00 50%, #bdbd00 100%);
        background-image: linear-gradient(to bottom, #581bbb, #581bbb 50%, #bdbd00 50%, #bdbd00 100%);
        -webkit-animation: hulkPants 7s infinite;
        animation: hulkPants 7s infinite;
    }
    .hulk .left-leg::after {
        content: "";
        width: 0.938em;
        height: 0.875em;
        position: absolute;
        background: #581bbb;
        margin-left: -0.938em;
    }
    .hulk .left-leg::before {
        content: "";
        width: 1.125em;
        height: 0.188em;
        position: absolute;
        margin-top: 6.25em;
        margin-left: 0.625em;
        background: #bdbd00;
    }

    .captain {
        position: absolute;
        z-index: 1000;
        margin-left: 355px;
        bottom: 0px;
        margin-bottom: 146px;
    }
    .captain .head {
        width: 21px;
        height: 36px;
        background: #43abf0;
        position: absolute;
        margin-top: -36px;
        margin-left: 44px;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        color: #fff;
        text-align: center;
        font-size: 0.6em;
        font-weight: bold;
    }
    .captain .head::before {
        content: "";
        width: 21px;
        height: 17px;
        background: #fdb0a4;
        position: absolute;
        margin-left: -7px;
        margin-top: 19px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        box-shadow: inset 8px 0 0 0 #fc9a8b;
    }
    .captain .head::after {
        content: "";
        width: 4px;
        height: 4px;
        position: absolute;
        background: #fdb0a4;
        border-radius: 50%;
        margin-top: 16px;
        margin-left: -5px;
    }
    .captain .body {
        height: 53px;
        width: 107px;
        border-radius: 0 0 110px 110px;
        background: #43abf0;
        box-shadow: inset 8px 0px 0 0 #29708f, inset 17px 0px 0 0 #35a5ef;
        z-index: 5;
    }
    .captain .body::before {
        content: "";
        width: 133px;
        height: 30px;
        margin-left: -14px;
        position: absolute;
        border-radius: 30px;
        background-image: -webkit-linear-gradient(left, #29708f, #43abf0);
        background-image: linear-gradient(to right, #29708f, #43abf0);
        z-index: -1;
    }
    .captain .body::after {
        content: '';
        width: 30px;
        height: 10px;
        position: absolute;
        margin-top: 49px;
        margin-left: 38px;
        background-image: -webkit-linear-gradient(left, #9d2f00, #9d2f00 30%, #ffffcb 30%, #ffffcb 70%, #b63700 70%, #b63700 100%);
        background-image: linear-gradient(to right, #9d2f00, #9d2f00 30%, #ffffcb 30%, #ffffcb 70%, #b63700 70%, #b63700 100%);
    }
    .captain .body .star {
        display: block;
        color: #fff;
        width: 0px;
        height: 0px;
        border-right: 10px solid transparent;
        border-bottom: 7px solid #fff;
        border-left: 10px solid transparent;
        margin-top: 18px;
        margin-left: 43px;
        position: absolute;
        -webkit-transform: rotate(35deg);
        transform: rotate(35deg);
    }
    .captain .body .star::before {
        border-bottom: 8px solid #fff;
        border-left: 3px solid transparent;
        border-right: 3px solid transparent;
        position: relative;
        height: 0;
        width: 0;
        top: -4px;
        left: -6px;
        display: block;
        content: '';
        -webkit-transform: rotate(-35deg);
        transform: rotate(-35deg);
    }
    .captain .body .star::after {
        position: absolute;
        display: block;
        color: red;
        top: 0px;
        left: -10px;
        width: 0px;
        height: 0px;
        border-right: 10px solid transparent;
        border-bottom: 7px solid #fff;
        border-left: 10px solid transparent;
        -webkit-transform: rotate(-70deg);
        transform: rotate(-70deg);
        content: '';
    }
    .captain .arms {
        width: 10px;
        height: 60px;
        position: absolute;
        background: #ffffcb;
        margin-top: -30px;
        margin-left: 10px;
        box-shadow: 75px 0 0 0 #ffffcb;
        z-index: -1;
    }
    .captain .arms::before {
        content: "";
        width: 28px;
        height: 50px;
        position: absolute;
        background: #ffffcb;
        border-radius: 30% 50%;
        margin-left: -18px;
        margin-top: -3px;
        box-shadow: 95px 0 0 0 #ffffcb;
    }
    .captain .shield {
        width: 40px;
        height: 40px;
        background: #43abf0;
        border-radius: 50%;
        position: absolute;
        z-index: 100;
        margin-left: 75px;
        margin-top: 30px;
        box-shadow: 0 0 0 8px #ffffcb, 0 0 0 18px #c53905;
        -webkit-transform: rotate(-30deg);
        transform: rotate(-30deg);
    }
    .captain .shield .star {
        display: block;
        color: #fff;
        width: 0px;
        height: 0px;
        border-right: 10px solid transparent;
        border-bottom: 7px solid #fff;
        border-left: 10px solid transparent;
        margin-top: 15px;
        margin-left: 10px;
        position: absolute;
        -webkit-transform: rotate(35deg);
        transform: rotate(35deg);
    }
    .captain .shield .star::before {
        border-bottom: 8px solid #fff;
        border-left: 3px solid transparent;
        border-right: 3px solid transparent;
        position: relative;
        height: 0;
        width: 0;
        top: -4px;
        left: -6px;
        display: block;
        content: '';
        -webkit-transform: rotate(-35deg);
        transform: rotate(-35deg);
    }
    .captain .shield .star::after {
        position: absolute;
        display: block;
        color: red;
        top: 0px;
        left: -10px;
        width: 0px;
        height: 0px;
        border-right: 10px solid transparent;
        border-bottom: 7px solid #fff;
        border-left: 10px solid transparent;
        -webkit-transform: rotate(-70deg);
        transform: rotate(-70deg);
        content: '';
    }
    .captain .hands {
        width: 15px;
        height: 30px;
        position: absolute;
        margin-top: 29px;
        margin-left: 8px;
        background: #a73200;
        -webkit-transform: skew(10deg);
        transform: skew(10deg);
    }
    .captain .hands::before {
        content: "";
        width: 10px;
        height: 30px;
        background: #b3b300;
        position: absolute;
        margin-left: 12px;
        -webkit-transform: skew(-10deg);
        transform: skew(-10deg);
    }
    .captain .hands::after {
        content: "";
        width: 13px;
        height: 13px;
        border-radius: 50%;
        position: absolute;
        margin-top: 21px;
        margin-left: -1px;
        background: #a73200;
    }
    .captain .legs {
        width: 8px;
        height: 100px;
        background: #29708f;
        position: absolute;
        margin-left: 38px;
        margin-top: 5px;
        box-shadow: 22px 0 0 0 #43abf0;
    }
    .captain .legs::before {
        content: "";
        width: 30px;
        height: 5px;
        background: #501800;
        position: absolute;
    }
    .captain .legs::after {
        content: "";
        width: 14px;
        height: 9px;
        background-image: -webkit-linear-gradient(left, #29708f, #43abf0);
        background-image: linear-gradient(to right, #29708f, #43abf0);
        position: absolute;
        margin-left: 8px;
        margin-top: 5px;
    }
    .captain .boots {
        width: 10px;
        height: 41px;
        position: absolute;
        margin-top: 105px;
        margin-left: 37px;
        background: #832800;
        box-shadow: 22px 0 0 0 #b63700;
    }
    .captain .boots::after {
        content: "";
        width: 25px;
        height: 2px;
        position: absolute;
        background: #832800;
        margin-top: 39px;
        margin-left: -15px;
        box-shadow: 40px 0 0 0 #b63700;
    }
    .captain .boots::before {
        content: "";
        width: 18px;
        height: 12px;
        background: #832800;
        position: absolute;
        margin-left: -4px;
        box-shadow: 22px 0 0 0 #b63700;
    }

    .ironman {
        position: absolute;
        z-index: 900;
        margin-left: 0;
        margin-bottom: 135px;
        bottom: 0;
    }
    .ironman .helmet {
        width: 22px;
        height: 34px;
        background: #ab3300;
        position: absolute;
        margin-top: -33px;
        margin-left: 44px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        overflow: hidden;
    }
    .ironman .helmet::before {
        content: "";
        width: 7px;
        height: 2px;
        background: white;
        position: absolute;
        margin-top: 10px;
        margin-left: 0px;
        z-index: 3;
        box-shadow: 15px 0 0 0 white;
    }
    .ironman .mask {
        width: 10px;
        height: 18px;
        background: #deb800;
        position: relative;
        margin-top: 7px;
        margin-left: 6px;
    }
    .ironman .mask::before {
        content: "";
        width: 13px;
        height: 18px;
        background: #deb800;
        position: absolute;
        -webkit-transform: rotate(60deg);
        transform: rotate(60deg);
        margin-top: -3px;
        margin-left: 4px;
    }
    .ironman .mask::after {
        content: "";
        width: 13px;
        height: 18px;
        background: #deb800;
        position: absolute;
        -webkit-transform: rotate(-60deg);
        transform: rotate(-60deg);
        margin-top: -3px;
        margin-left: -5px;
    }
    .ironman .body {
        height: 53px;
        width: 107px;
        border-radius: 0 0 110px 110px;
        background: #ab3300;
        box-shadow: inset 10px 0px 0 0 #922b00, inset 20px 0px 0 0 #9c2e00;
        padding-top: 11px;
        z-index: 50;
    }
    .ironman .body::before {
        content: "";
        width: 32px;
        height: 32px;
        background: #922b00;
        position: absolute;
        border-radius: 50%;
        margin-left: -15px;
        margin-top: -18px;
        box-shadow: 105px 0 0 0 #ab3300;
        z-index: 50;
    }
    .ironman .body::after {
        content: "";
        width: 40px;
        height: 30px;
        position: absolute;
        margin-top: 20px;
        margin-left: 35px;
        background-image: -webkit-linear-gradient(left, #922b00, #922b00 15%, #9c2e00 15%, #9c2e00 29%, #ab3300 20%, #ab3300 20%);
        background-image: linear-gradient(to right, #922b00, #922b00 15%, #9c2e00 15%, #9c2e00 29%, #ab3300 20%, #ab3300 20%);
    }
    .ironman .power {
        width: 17px;
        height: 17px;
        background: white;
        border-radius: 50%;
        margin: 0 auto;
        box-shadow: 0 0 0 5px #deb800;
    }
    .ironman .left-leg {
        width: 10px;
        height: 90px;
        background: #c98700;
        position: absolute;
        margin-left: 35px;
        margin-top: 12px;
        border-top-right-radius: 10px;
    }
    .ironman .left-leg::before {
        content: "";
        width: 25px;
        height: 65px;
        background: #922b00;
        position: absolute;
        border-top-right-radius: 20px;
        margin-top: 60px;
        margin-left: -10px;
    }
    .ironman .right-leg {
        width: 10px;
        height: 90px;
        background: #deb800;
        position: absolute;
        margin-left: 65px;
        margin-top: 12px;
        border-top-left-radius: 10px;
    }
    .ironman .right-leg::before {
        content: "";
        width: 25px;
        height: 65px;
        background: #ab3300;
        position: absolute;
        border-top-left-radius: 20px;
        margin-top: 60px;
        margin-left: -5px;
    }
    .ironman .left-arm {
        width: 10px;
        height: 45px;
        background: #c98700;
        position: absolute;
        margin-left: -6px;
        margin-top: 18px;
        border-bottom-left-radius: 20px;
    }
    .ironman .left-arm::before {
        content: "";
        height: 60px;
        width: 31px;
        border-radius: 60px 0px 0px 60px;
        background: #922b00;
        position: absolute;
        margin-top: 30px;
        margin-left: -21px;
        z-index: -1;
    }
    .ironman .left-arm::after {
        content: "";
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #922b00;
        position: absolute;
        margin-top: 80px;
        margin-left: 5px;
    }
    .ironman .right-arm {
        width: 10px;
        height: 45px;
        background: #c98700;
        position: absolute;
        margin-left: 105px;
        margin-top: 18px;
        border-bottom-right-radius: 20px;
    }
    .ironman .right-arm::before {
        content: "";
        height: 60px;
        width: 31px;
        border-radius: 0px 60px 60px 0px;
        background: #ab3300;
        position: absolute;
        margin-top: 30px;
        margin-left: 0px;
        z-index: -1;
    }
    .ironman .right-arm::after {
        content: "";
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ab3300;
        position: absolute;
        margin-top: 80px;
        margin-left: -5px;
    }

    .thor {
        position: absolute;
        margin-left: 118px;
        z-index: 1000;
        margin-bottom: 220px;
        bottom: 0;
    }
    .thor .helmet {
        width: 0;
        height: 0;
        position: absolute;
        border-left: 18px solid transparent;
        border-right: 18px solid transparent;
        border-bottom: 40px solid #d7a900;
        margin-top: -40px;
        margin-left: 42px;
        box-shadow: 0 -18px 0 -16px #66999a;
    }
    .thor .helmet::before {
        content: "";
        width: 13px;
        height: 25px;
        position: absolute;
        background: #66999a;
        margin-left: -19px;
        margin-top: -12px;
        border-bottom-left-radius: 10px;
        border-top-right-radius: 15px;
        box-shadow: inset -3px 0px 0 transparent;
    }
    .thor .helmet::after {
        content: "";
        width: 13px;
        height: 25px;
        position: absolute;
        background: #89b9ef;
        margin-left: 10px;
        margin-top: -12px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 15px;
        box-shadow: inset -3px 0px 0 transparent;
    }
    .thor .head {
        width: 21px;
        height: 40px;
        background-image: -webkit-linear-gradient(#72a9d5, #72a9d5 32%, #ffdeef 10%);
        background-image: linear-gradient(#72a9d5, #72a9d5 32%, #ffdeef 10%);
        position: absolute;
        margin-top: -40px;
        margin-left: 50px;
        border-radius: 30px 30px 0 0;
        overflow: hidden;
    }
    .thor .head::after {
        content: "";
        width: 10px;
        height: 17px;
        background: #d7a900;
        position: absolute;
        border-radius: 10px 10px 0px 0px;
        margin-top: 20px;
        margin-left: 5px;
    }
    .thor .head .beard {
        width: 21px;
        height: 5px;
        background: #d7a900;
        position: absolute;
        margin-top: 12px;
    }
    .thor .head .beard::before {
        content: "";
        width: 11px;
        height: 5px;
        background: #d7a900;
        position: absolute;
        margin-left: 12px;
        margin-top: 17px;
        -webkit-transform: rotate(-40deg);
        transform: rotate(-40deg);
    }
    .thor .head .beard::after {
        content: "";
        width: 11px;
        height: 5px;
        background: #d7a900;
        position: absolute;
        margin-left: -3px;
        margin-top: 17px;
        -webkit-transform: rotate(40deg);
        transform: rotate(40deg);
    }
    .thor .body {
        border-top: 65px solid #004278;
        border-left: 40px solid transparent;
        border-right: 40px solid transparent;
        height: 0;
        width: 120px;
        position: absolute;
    }
    .thor .body::before {
        content: "";
        width: 40px;
        height: 5px;
        position: absolute;
        background-image: -webkit-linear-gradient(left, #c98700, #d7a900);
        background-image: linear-gradient(to right, #c98700, #d7a900);
    }
    .thor .body::after {
        content: "";
        width: 30px;
        height: 30px;
        border-radius: 50%;
        position: absolute;
        background: #94bdef;
        margin-top: -60px;
        margin-left: -17px;
        box-shadow: 45px 0 0 0 #94bdef, 10px 29px 0 -5px #94bdef, 35px 29px 0 -5px #94bdef;
    }
    .thor .arm-right {
        width: 40px;
        height: 40px;
        background: #ffcee7;
        border-radius: 50%;
        position: absolute;
        margin-left: 100px;
        box-shadow: -5px 0 0 #ffcee7, -10px 0 0 #ffcee7, -15px 0 0 #ffcee7, -20px 0 0 #ffcee7, -20px 90px 0 -15px #ffcee7, -20px 95px 0 -15px #ffcee7, -20px 100px 0 -15px #ffcee7;
    }
    .thor .arm-right::before {
        content: "";
        width: 40px;
        height: 100px;
        position: absolute;
        background: #ffcee7;
        margin-left: -5px;
        margin-top: 10px;
        border-bottom-right-radius: 30px;
    }
    .thor .arm-right::after {
        content: "";
        width: 10px;
        height: 5px;
        background: #290000;
        position: absolute;
        margin-top: 113px;
        margin-left: -5px;
    }
    .thor .arm-left {
        width: 40px;
        height: 40px;
        background: #ffc688;
        border-radius: 50%;
        position: absolute;
        margin-left: 0px;
        box-shadow: -5px 0 0 #ffc688, -10px 0 0 #ffc688, -15px 0 0 #ffc688, -20px 0 0 #ffc688, 0 90px 0 -15px #ffc688, 0 95px 0 -15px #ffc688, 0 100px 0 -15px #ffc688;
    }
    .thor .arm-left::before {
        content: "";
        width: 40px;
        height: 100px;
        position: absolute;
        background: #ffc688;
        margin-left: -15px;
        margin-top: 10px;
        border-bottom-left-radius: 30px;
    }
    .thor .arm-left::after {
        content: "";
        width: 10px;
        height: 5px;
        background: #290000;
        position: absolute;
        margin-top: 113px;
        margin-left: 15px;
    }
    .thor .legs {
        width: 13px;
        height: 67px;
        background: #002e54;
        position: absolute;
        margin-top: 70px;
        left: 40px;
        border-radius: 0 0 30px 30px;
        box-shadow: 27px 0 0 0 #004278;
    }
    .thor .legs::before {
        content: "";
        width: 10px;
        height: 75px;
        position: absolute;
        background: #002e54;
        margin-left: 5px;
        box-shadow: 0 75px 0 0 #b75600, 21px 0 0 0 #004278, 21px 75px 0 0 #ce9700;
    }
    .thor .legs::after {
        content: "";
        width: 11px;
        height: 10px;
        background-image: -webkit-linear-gradient(#002e54, #004278);
        background-image: linear-gradient(#002e54, #004278);
        position: absolute;
        margin-left: 15px;
    }
    .thor .cap {
        width: 120px;
        height: 200px;
        position: absolute;
        background: #a72300;
        z-index: 0;
        box-shadow: inset 10px 0 0 #721200, inset 20px 0 0 #7b1e00;
    }
    .thor .hammer {
        width: 42px;
        height: 27px;
        position: absolute;
        background: #382727;
        /* fallback */
        background: -webkit-linear-gradient(315deg, rgba(0, 0, 0, 0) 5px, #382727 0%) top left, -webkit-linear-gradient(225deg, rgba(0, 0, 0, 0) 5px, #382727 0%) top right, -webkit-linear-gradient(135deg, rgba(0, 0, 0, 0) 5px, #382727 0%) bottom right, -webkit-linear-gradient(45deg, rgba(0, 0, 0, 0) 5px, #382727 0%) bottom left;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0) 5px, #382727 0%) top left, linear-gradient(225deg, rgba(0, 0, 0, 0) 5px, #382727 0%) top right, linear-gradient(315deg, rgba(0, 0, 0, 0) 5px, #382727 0%) bottom right, linear-gradient(45deg, rgba(0, 0, 0, 0) 5px, #382727 0%) bottom left;
        background-size: 52% 52%;
        background-repeat: no-repeat;
        margin-top: 148px;
        margin-left: 1px;
    }
    .thor .hammer::before {
        content: "";
        width: 6px;
        height: 25px;
        background: #291d1d;
        position: absolute;
        margin-top: -23px;
        margin-left: 18px;
    }
    .thor .hammer::after {
        content: "";
        width: 6px;
        height: 3px;
        position: absolute;
        background: #291d1d;
        margin-top: 25px;
        margin-left: 18px;
    }
    .thor .feet {
        width: 25px;
        height: 2px;
        position: absolute;
        background: #b75600;
        margin-top: 220px;
        margin-left: 30px;
        box-shadow: 36px 0 0 #ce9700;
    }
    .thor .feet::after {
        content: "";
        height: 10px;
        width: 20px;
        position: absolute;
        border-radius: 90px 90px 0 0;
        background: #ce9700;
        margin-top: -85px;
        margin-left: 8px;
        box-shadow: 24px 0 0 #b75600;
    }

    .awwwards {
        position: absolute;
        top: 30px;
        left: 70px;
    }

    .awwwards a {
        color: #ab3300;
        text-decoration: none;
        border-bottom: 1px solid #ab3300;
    }

    /*Hand Animation*/
    @-webkit-keyframes shake {
        78%, 80%,82%,84%,86%,88%,90%,92% {
            top: 3px;
            left: 2px;
        }
        79%,81%,83%,85%,87%,89%,91% {
            top: 0px;
            left: -2px;
        }
    }
    @keyframes shake {
        78%, 80%,82%,84%,86%,88%,90%,92% {
            top: 3px;
            left: 2px;
        }
        79%,81%,83%,85%,87%,89%,91% {
            top: 0px;
            left: -2px;
        }
    }
    @-webkit-keyframes mouthOpen {
        77% {
            height: 0px;
        }
        80% {
            height: 22px;
        }
        93% {
            height: 22px;
            opacity: 1;
        }
        95% {
            height: 0px;
            opacity: 0;
        }
    }
    @keyframes mouthOpen {
        77% {
            height: 0px;
        }
        80% {
            height: 22px;
        }
        93% {
            height: 22px;
            opacity: 1;
        }
        95% {
            height: 0px;
            opacity: 0;
        }
    }
    @-webkit-keyframes hulkHands {
        6% {
            box-shadow: 11.1em 0 0 #b5b500;
        }
        77% {
            box-shadow: 11.1em 0 0 #b5b500;
            margin-left: -2.1em;
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        80% {
            margin-left: 0.5em;
            box-shadow: 8.5em 0 0 #b5b500;
            -webkit-transform: translate(-0.9em, -19.9em);
            transform: translate(-0.9em, -19.9em);
        }
        93% {
            margin-left: 0.5em;
            box-shadow: 8.5em 0 0 #b5b500;
            -webkit-transform: translate(-0.9em, -19.9em);
            transform: translate(-0.9em, -19.9em);
        }
        94% {
            box-shadow: 11.1em 0 0 #b5b500;
            margin-left: -2.1em;
            -webkit-transform: translate(0em, 0em);
            transform: translate(0em, 0em);
        }
    }
    @keyframes hulkHands {
        6% {
            box-shadow: 11.1em 0 0 #b5b500;
        }
        77% {
            box-shadow: 11.1em 0 0 #b5b500;
            margin-left: -2.1em;
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
        }
        80% {
            margin-left: 0.5em;
            box-shadow: 8.5em 0 0 #b5b500;
            -webkit-transform: translate(-0.9em, -19.9em);
            transform: translate(-0.9em, -19.9em);
        }
        93% {
            margin-left: 0.5em;
            box-shadow: 8.5em 0 0 #b5b500;
            -webkit-transform: translate(-0.9em, -19.9em);
            transform: translate(-0.9em, -19.9em);
        }
        94% {
            box-shadow: 11.1em 0 0 #b5b500;
            margin-left: -2.1em;
            -webkit-transform: translate(0em, 0em);
            transform: translate(0em, 0em);
        }
    }
    @-webkit-keyframes hulkRightarm {
        77% {
            margin-top: 1em;
            margin-left: 2em;
            clip: rect(-1em, 11.2em, 12.55em, 7.625em);
        }
        80% {
            margin-top: -8.125em;
            margin-left: 2.5em;
            clip: rect(0.563em, 13.363em, 13.75em, 6.625em);
        }
        93% {
            margin-top: -8.125em;
            margin-left: 2.5em;
            clip: rect(0.563em, 13.363em, 13.75em, 6.625em);
        }
        95% {
            margin-top: 1em;
            margin-left: 2em;
            clip: rect(-1em, 11.2em, 12.55em, 7.625em);
        }
    }
    @keyframes hulkRightarm {
        77% {
            margin-top: 1em;
            margin-left: 2em;
            clip: rect(-1em, 11.2em, 12.55em, 7.625em);
        }
        80% {
            margin-top: -8.125em;
            margin-left: 2.5em;
            clip: rect(0.563em, 13.363em, 13.75em, 6.625em);
        }
        93% {
            margin-top: -8.125em;
            margin-left: 2.5em;
            clip: rect(0.563em, 13.363em, 13.75em, 6.625em);
        }
        95% {
            margin-top: 1em;
            margin-left: 2em;
            clip: rect(-1em, 11.2em, 12.55em, 7.625em);
        }
    }
    @-webkit-keyframes hulkLeftarm {
        77% {
            margin-top: 1em;
            margin-left: -4.3em;
            clip: rect(-0.188em, 6.1em, 13.875em, 2.3em);
        }
        80% {
            margin-top: -8.125em;
            margin-left: -4.375em;
            clip: rect(-0.188em, 6.563em, 13.875em, 0em);
        }
        93% {
            margin-top: -8.125em;
            margin-left: -4.375em;
            clip: rect(-0.188em, 6.563em, 13.875em, 0em);
        }
        95% {
            margin-top: 1em;
            margin-left: -4.3em;
            clip: rect(-0.188em, 6.1em, 13.875em, 2.3em);
        }
    }
    @keyframes hulkLeftarm {
        77% {
            margin-top: 1em;
            margin-left: -4.3em;
            clip: rect(-0.188em, 6.1em, 13.875em, 2.3em);
        }
        80% {
            margin-top: -8.125em;
            margin-left: -4.375em;
            clip: rect(-0.188em, 6.563em, 13.875em, 0em);
        }
        93% {
            margin-top: -8.125em;
            margin-left: -4.375em;
            clip: rect(-0.188em, 6.563em, 13.875em, 0em);
        }
        95% {
            margin-top: 1em;
            margin-left: -4.3em;
            clip: rect(-0.188em, 6.1em, 13.875em, 2.3em);
        }
    }
    .footer--love {
        position: absolute;
        right: 1.5rem;
        bottom: 2.5rem;
        font-size: 0.85rem;
        font-family: 'Lato', sans-serif;
        z-index: 9999;
    }
    .footer--love .love-heart {
        background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/42764/heart-smil.svg);
        width: 16px;
        height: 16px;
        display: inline-block;
        margin: 0 5px 0 2px;
        vertical-align: top;
    }
    .footer--love a {
        color: #43abf0;
    }

</style>
<div class="container">
    <div id="index" >
        <!--chart dashboard start-->
        <div id="chart-dashboard">
            
            
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <div class="col s12 m12 l12">  
                    <strong>ANALISIS ABC DE INVENTARIOS :</strong>
                      <div class="row">
                        <div class="col s4 m4 l4">
                       <strong>MES DE ANALISIS :</strong> 
                       <select id="mes" name="mes">
                           <option value="0">Ninguno</option>
                           <option value="1">Enero</option>
                           <option value="2">Febrero</option>
                           <option value="3">Marzo</option>
                           <option value="4">Abril</option>
                           <option value="5">Mayo</option>
                           <option value="6">Junio</option>
                           <option value="7">Julio</option>
                           <option value="8">Agosto</option>
                           <option value="9">Septiembre</option>
                           <option value="10">Octubre</option>
                           <option value="11">Noviembre</option>
                           <option value="12">Diciembre</option>
                       </select>       
                        </div>  
                          <div class="col s4 m4 l4"> 
                              <strong>  ANIO DE ANALISIS :</strong> 
                          <select id="anio" name="year">
                              <script>
                              var myDate = new Date();
                              var year = myDate.getFullYear();
                              for(var i = 1900; i < year+1; i++){
                                  if(i==1900){
                                      document.write('<option value="'+0+'">'+'ninguno'+'</option>');
                                  }else {
                                        document.write('<option value="'+i+'">'+i+'</option>');
                                  }
                                    
                                      
                              }
                              </script>
</select>
                          </div>
                        <div class="col s4 m4 l4">
                       <strong>SELECCIONE ALMACEN :</stron> 
                       <div class="col s12 m12 l12">
                            <select id="almacencombo">
                                <option>Cambiar Almacen a</option>
                            </select>
                        </div>                                    
                        </div>                     
                        </br>
                        <div class="col s12 m2 s2" style="padding: 10px; text-align: center;"> 
                    <a class="btn"  OnClick='consultar();'>Consultar</a>
                        </div>  
                    </div>
                    
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up cyan darken-1">
                                <div>
                                    <span class="chart-title white-text"><strong>ANALISIS ABC TOTAL</strong></span>
                                </div>
                                <div class="trending-line-chart-wrapper">
                                    <canvas id="trending-line-chart" height="100"></canvas>                                    
                                </div>                                
                            </div>
                            <div class="row" align= "right">
                                <div class="col s10 m10 l10">
                                <div class="card-reveal22">
                            <span class="card-title grey-text text-darken-4"><strong>ANALISIS TOTAL "por utilizacion y valor" DE INVENTARIOS</strong><i class="mdi-navigation-close right"></i></span>
                                       <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="item-sold">    Part_estimada</th>
                                        <th data-field="item-sold">Participacion</th>
                                        <th data-field="item-price">N</th>
                                        <th data-field="item-price">N-participacion</th>
                                        <th data-field="item-price">Ventas</th>
                                        <th data-field="item-price">Part-Ventas</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="datos22">
                                    
                                </tbody>
                                     </table>
                                     </div>
                                </div>
                                </div>
                        </div>
        
                    <div class="card-content">
    <a class="btn-floating btn-move-up waves-effect waves-light darken-2 right" style="background-color: #ff1744 !important">
        <i class="mdi-content-add activator"></i></a>      
                    </div>                            
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><strong>MODELO DE PARETTO ANALISIS INDIVIDUAL ABC "por utilizacion y valor" DE INVENTARIOS</strong><i class="mdi-navigation-close right"></i></span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="month">NOMB.PROD</th>
                                        <th data-field="item-sold">PREC.PROMEDIO</th>
                                        <th data-field="item-price">UNID.VENDIDAS</th>
                                        <th data-field="item-price">VALOR.VENDIDO</th>
                                        <th data-field="item-price">PARTICIPACION</th>
                                        <th data-field="item-price">PART.ACUMULADA</th>
                                        <th data-field="item-price">CLASIFICACION</th>
                                    </tr>
                                </thead>
                                <tbody id="datos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                  
                </div>
            </div>
            <!--div class="row">
                 <div class="col s12 m12 l12">
                     <table class="egt" >
                         <tr>
                             <th></th>
                             <th colspan="5" bgcolor="#BFF9FF">Descripcion</th>                                                         
                         </tr>
                         <tr>
                             <th colspan="0" bgcolor="#BFF9FF">Variables</th>
                              
                         </tr>
                        
                         <tr>
                           <th colspan="1" bgcolor="#BFF9FF">Emn</th>
                          <td>Cmn X Tr</td>
                         </tr>
                         <tr>
                             <td colspan="1" bgcolor="#BFF9FF">Pp</td>
                             <th>(Cp X Tr)+ Emn </th>                             
                         </tr>
                         <tr>
                             <td colspan="1" bgcolor="#BFF9FF">Emx</td>
                             <th>(Cmx X Tr)+ Emn </th>
                         </tr>
                         <tr>
                         <td colspan="1"bgcolor="#BFF9FF">Cp</td>
                         <th> Emx-E</th>
                         </tr>
                     </table>
                </div>
                
                <div class="row">
                 </br>
                 </br>
                /******************************/
                /******************************/
                /******************************/
                              
                 </br>
             </div>
                
                 <div class="col s12 m12 l12">
                     <table class="egt" >
                         <tr>
                             <th></th>
                             <th bgcolor="#FDCBAB">Descripcion</th>                                                         
                         </tr>
                         
                         <tr>
                             <th bgcolor="#FDCBAB">Variables</th>                              
                         </tr>
                         
                         <tr>
                           <td bgcolor="#FDCBAB">Emn</td>
                          <th bgcolor="#EAF1F6">Existencia M&iacute;nima(Inventario de Seguridad)</th>
                         </tr>
                         
                         <tr>
                             <td bgcolor="#FDCBAB">Pp</td>
                             <th bgcolor="#EAF1F6">Punto De Pedido/Punto de Reorden</th>                             
                         </tr>
                         
                         <tr>
                             <td bgcolor="#FDCBAB">Emx</td>
                             <th bgcolor="#EAF1F6">Existencia M&aacute;xima</th>
                         </tr>
                         
                         <tr><td bgcolor="#FDCBAB">Cp</td>
                         <th bgcolor="#EAF1F6">Cantidad de Pedido</th>
                         </tr>
                         
                         <!--OTRAS VARIABLES-->
                         <!--tr><td bgcolor="#20C2FF">Cmin</td>
                         <th bgcolor="#BFF9FF">Consumo Minimo Diario</th>
                         </tr>
                          <tr><td bgcolor="#20C2FF">Tr</td>
                         <th bgcolor="#BFF9FF">Tiempo de Reposicion de Inventario</th>
                         </tr>
                          <tr><td bgcolor="#20C2FF">cp</td>
                         <th bgcolor="#BFF9FF">Consumo Medio Diario</th>
                         </tr>
                          <tr><td bgcolor="#20C2FF">Cmx</td>
                         <th bgcolor="#BFF9FF">Consumo Maximo Diario</th>
                         </tr>
                         <tr><td bgcolor="#20C2FF">E</td>
                         <th bgcolor="#BFF9FF">Existencia Actual</th>
                         </tr>
                     </table>
                </div>           
            </div-->
                
            <!--div class="row">
                <div class="col s12 m12 l12">
                 
                </div>
            </div-->
            <div class="row">gg doble g 
                <div class="col s12 m5 l4">
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up purple">
                                <div>
                                    <span class="chart-title white-text"><strong>PRODUCTOS POR CATEGORIAS</strong></span>
                                </div>
                                <div class="doughnut-chart-wrapper">
                                    <canvas id="doughnut-chart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="trending-bar-chart-wrapper white">
                                <div id="listacategorias" class="row doughnut-chart-legend black-text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up orange accent-3">
                                <div>
                                    <span class="chart-title white-text"><strong>GANANCIAS VENTAS MENSUALES</strong></span>
                                </div>
                                <div class="trending-line-chart-wrapper">
                                    <canvas id="trending-line-chart2" height="70"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-content white">
                            <a class="btn-floating btn-move-up waves-effect waves-light darken-2 left" style="background-color: #0091ea !important"><i class="mdi-content-add activator"></i></a>
                            <div class="col s12 m12 l12">
                                <div class="line-chart-wrapper">
                                    <p class="margin black-text"><strong>TIPOS DE PAGO EN LAS VENTAS</strong></p>
                                    <canvas id="trending-bar-chart2" height="55"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><strong>GANACIAS MENSUALES</strong><i class="mdi-navigation-close right"></i></span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="country-name">MES</th>
                                        <th data-field="item-sold">CANTIDAD DE VENTAS</th>
                                        <th data-field="total-profit">TOTAL VENDIDO</th>
                                    </tr>
                                </thead>
                                <tbody id="datos-mensuales">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--div class="row">
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up red accent-2">
                                <div>
                                    <span class="chart-title white-text"><strong>TOP 10: PRODUCTOS MAS VENDIDOS</strong></span>
                                </div>
                                <div class="trending-line-chart-wrapper">
                                    <canvas id="line-chart2" height="90"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-content white">
                            <a class="btn-floating btn-move-up waves-effect waves-light darken-2 right" style="background-color: #0091ea !important"><i class="mdi-content-add activator"></i></a>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><strong>PRODUCTOS MAS VENDIDOS</strong><i class="mdi-navigation-close right"></i></span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="country-name">PRODUCTO</th>
                                        <th data-field="item-sold">CODIGO DE BARRA</th>
                                        <th data-field="item-sold">COLOR</th>
                                        <th data-field="item-sold">TALLA/TAMAÑO</th>
                                        <th data-field="item-sold">CANT. VENDIDA</th>
                                        <th data-field="total-profit">TOTAL VENDIDO</th>
                                    </tr>
                                </thead>
                                <tbody id="datos-top-productos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div-->
            <!--div class="row"> 
                <div class="col s12 m6 l6">
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up teal accent-4">
                                <span class="chart-title white-text"><strong>TOP 5: CLIENTE FACTURAS</strong></span>
                                <div class="doughnut-chart-wrapper">
                                    <canvas id="doughnut-chart2" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-content white">
                            <a class="btn-floating btn-move-up waves-effect waves-light darken-2 left" style="background-color: #ff1744 !important"><i class="mdi-content-add activator"></i></a>
                            <div class="trending-bar-chart-wrapper white">
                                <div class="col s12 m12 l12" style="padding-bottom: 20px;">
                                    <div id="listaclientefacturas" class="row doughnut-chart-legend black-text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><strong>TOP 5: CLIENTE FACTURAS</strong><i class="mdi-navigation-close right"></i></span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="country-name">CLIENTE</th>
                                        <th data-field="item-sold">NIT</th>
                                        <th data-field="item-sold">CANTIDAD</th>
                                        <th data-field="total-profit">TOTAL VENDIDO</th>
                                    </tr>
                                </thead>
                                <tbody id="datos-cliente-facturas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l6">
                    <div class="card">
                        <div class="card-move-up waves-effect waves-block waves-light">
                            <div class="move-up blue accent-4">
                                <span class="chart-title white-text"><strong>TOP 5: CLIENTES EN VENTAS</strong></span>
                                <div class="doughnut-chart-wrapper">
                                    <canvas id="doughnut-chart3" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-content white">
                            <a class="btn-floating btn-move-up waves-effect waves-light darken-2 left" style="background-color: #ff1744 !important"><i class="mdi-content-add activator"></i></a>
                            <div class="trending-bar-chart-wrapper white">
                                <div class="col s12 m12 l12" style="padding-bottom: 20px;">
                                    <div id="listaclientesventas" class="row doughnut-chart-legend black-text">
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><strong>TOP 5: CLIENTES EN VENTAS</strong><i class="mdi-navigation-close right"></i></span>
                            <table class="responsive-table">
                                <thead>
                                    <tr>
                                        <th data-field="country-name">CLIENTE</th>
                                        <th data-field="item-sold">NIT</th>
                                        <th data-field="item-sold">CANTIDAD</th>
                                        <th data-field="total-profit">TOTAL VENDIDO</th>
                                    </tr>
                                </thead>
                                <tbody id="datos-cliente-ventas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div-->
            
        </div>
        <!--chart dashboard end-->
    </div>
    <div id="avengers">
        <div class="wrappera">
            <div class="hulk">
                <div class="head"><div class="mouth"></div></div>
                <div class="right-arm"></div>
                <div class="fist"></div>
                <div class="left-arm"></div>
                <div class="body"></div>
                <div class="right-leg"></div>
                <div class="left-leg"></div>
            </div>

            <div class="captain">
                <div class="head">A</div>
                <div class="body"><div class="star"></div></div>
                <div class="arms"></div>
                <div class="shield"><div class="star"></div></div> 
                <div class="hands"></div>
                <div class="legs"></div>
                <div class="boots"></div>
            </div>

            <div class="ironman">
                <div class="helmet"><div class="mask"></div></div>
                <div class="right-arm"></div>
                <div class="left-arm"></div>
                <div class="body"><div class="power"></div></div>
                <div class="right-leg"></div>
                <div class="left-leg"></div>
            </div>  

            <div class="thor">
                <div class="helmet"></div>
                <div class="head">
                    <div class="beard"></div>
                </div>
                <div class="cap"></div>
                <div class="arm-right"></div>
                <div class="arm-left"></div>
                <div class="body"></div>
                <div class="hammer"></div>
                <div class="legs"></div>
                <div class="feet"></div>
            </div> 
        </div>
    </div>
</div>

<!--chart dashboard end-->
@section('scripts')
{!!Html::script('js/abc.min.js')!!}
{!!Html::script('js/abc.js')!!}
<!--{!!Html::script('../node_modules/highcharts/highcharts.js')!!}
{!!Html::script('../node_modules/highcharts/modules/exporting.js')!!}-->

<!--script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script-->
<!--script type="text/javascript" src="../../node_modules/highcharts/js/themes/gray.js"></script-->
@endsection
@stop