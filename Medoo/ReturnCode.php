<?php

/**
 * Class ReturnCode
 * 用来定义统一的错误码，主要用于规范Ret抛出的错误代码，同时也让异常具有意义
 * 使用实例
 * throw new PhalApi_Exception(
 *    T('NOT_EXISTS', [$name]) , ReturnCode::NOT_EXISTS
 * );
 */

class ReturnCode {

    const INVALID = -1;
    const DB_SAVE_ERROR = -2;
    const DB_READ_ERROR = -3;
    const CACHE_SAVE_ERROR = -4;
    const CACHE_READ_ERROR = -5;
    const FILE_SAVE_ERROR = -6;
    const LOGIN_ERROR = -7;
    const NOT_EXISTS = -8;

}