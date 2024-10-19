import React from "react";
import PropTypes from "prop-types";
import { AlertCircle } from "lucide-react";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"; // Assuming you're using a UI library

const InputError = ({ error }) => {
  if (!error) return null; // If no error, don't render anything

  return (
    <Alert className="text-xs mt-3" variant="destructive">
      <AlertCircle className="h-4 w-4" />
      <AlertTitle className="text-xs">Error</AlertTitle>
      <AlertDescription className="text-xs">
        {error}
      </AlertDescription>
    </Alert>
  );
};

InputError.propTypes = {
  error: PropTypes.string, // Prop type validation to ensure it's a string
};

export default InputError;
