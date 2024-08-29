<?php

namespace App\Util;

enum UserRole: string
{
    case BackEnd = "Back End";
    case FrontEnd = "Front End";
    case ComputerTechnician = "Computer technician";
    case ComputerSupportTechnician = "Computer support technician";
    case HelpDeskWorker = "Help desk worker";
    case HelpDeskAnalyst = "Help desk analyst";
    case HelpDeskSupport = "Help desk support";
    case HelpDeskTechnician = "Help desk technician";
    case DesktopSupportSpecialist = "Desktop support specialist";
    case ITSupport = "IT support technician";
    case ITTechnician = "IT technician";
    case ProblemManager = "Problem manager";
    case OperationsAnalyst = "Operations analyst";
    case TechnicalAssistanceSpecialist = "Technical assistance specialist";
    case TechnicalSpecialist = "Technical specialist";
    case TechnicalSupport = "Technical support";
    case SupportSpecialist = "Support specialist";
    case ComputerOperator = "Computer operator";
}
