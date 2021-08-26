@echo off

rem -------------------------------------------------------------
rem  Command line bootstrap script for Windows.
rem
rem  Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
rem  @link http://www.larva.com.cn/
rem -------------------------------------------------------------

@setlocal

set LARAVEL_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%LARAVEL_PATH%artisan" %*

@endlocal
