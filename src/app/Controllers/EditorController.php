<?php

class EditorController
{
    public function __construct(private PhotoModel $photoMOdel) {}

    public function showEditor()
    {
        render("EditorView");
    }
}
