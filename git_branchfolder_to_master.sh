#!/bin/bash
#This is a  bash script that accepts arguments from the user
while getopts a:b:c: option; do 
	case $option in
		a) targetbranch=$OPTARG;;
		b) sourcebranch=$OPTARG;;
    c) sourcefolder=$OPTARG;;
	esac
done
#echo "Target branch: $targetbranch Source branch: $sourcebranch Source folder: $sourcefolder"

git checkout $targetbranch
  git checkout $sourcebranch  -- $sourcefolder/*
  git add $sourcefolder
  git commit -m "Add folder $sourcefolder"
