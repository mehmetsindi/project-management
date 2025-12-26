<?php

namespace App\Services;

class GeminiAIService
{
    /**
     * Mock AI service that generates task descriptions
     * In production, this would call the actual Gemini AI API
     */
    public function generateTaskDescription(string $title): string
    {
        // Mock responses based on keywords in the title
        $mockResponses = [
            'design' => 'Create wireframes and mockups for the user interface. Collaborate with stakeholders to gather requirements and iterate on the design based on feedback.',
            'develop' => 'Implement the feature according to specifications. Write clean, maintainable code following best practices. Include unit tests and documentation.',
            'test' => 'Create comprehensive test cases covering all scenarios. Execute tests and document results. Report any bugs or issues found during testing.',
            'deploy' => 'Prepare the deployment package and verify all dependencies. Deploy to the target environment and perform smoke tests to ensure everything works correctly.',
            'fix' => 'Investigate the root cause of the issue. Implement a fix and verify it resolves the problem. Add regression tests to prevent future occurrences.',
            'review' => 'Conduct a thorough review of the code/document. Provide constructive feedback and suggestions for improvement. Ensure compliance with standards and best practices.',
            'meeting' => 'Prepare agenda and materials for the meeting. Facilitate discussion and ensure all participants have an opportunity to contribute. Document decisions and action items.',
            'research' => 'Gather information from reliable sources. Analyze findings and identify key insights. Prepare a summary report with recommendations.',
        ];

        // Check for keywords in the title
        $titleLower = strtolower($title);
        foreach ($mockResponses as $keyword => $response) {
            if (str_contains($titleLower, $keyword)) {
                return $response;
            }
        }

        // Default generic response
        return 'Complete this task according to the project requirements. Ensure quality standards are met and document your work appropriately. Coordinate with team members as needed.';
    }

    /**
     * Generate a task title suggestion based on context
     */
    public function suggestTaskTitle(string $context): array
    {
        // Mock title suggestions
        return [
            'Design user authentication flow',
            'Implement payment gateway integration',
            'Fix navigation menu bug',
            'Review code for security vulnerabilities',
            'Deploy to production environment',
        ];
    }
}
