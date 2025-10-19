@echo off
setlocal enabledelayedexpansion

REM Caminho do executável PHP corrigido
set PHP_PATH=C:\wamp64\bin\php\php8.2.26\php.exe

REM Diretório do projeto
set PROJECT_DIR=%cd%

echo Verificando sintaxe dos arquivos PHP no diretório %PROJECT_DIR%...

REM Loop para verificar todos os arquivos PHP no diretório e subdiretórios
for /r "%PROJECT_DIR%" %%f in (*.php) do (
    echo Verificando %%f ...
    "%PHP_PATH%" -l "%%f"
    if errorlevel 1 (
        echo ERRO encontrado em %%f
    ) else (
        echo Sem erros em %%f
    )
    echo.
)

echo Verificação concluída.
pause
