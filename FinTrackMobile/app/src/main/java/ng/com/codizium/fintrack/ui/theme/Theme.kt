package ng.com.codizium.fintrack.ui.theme

import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable

private val LightColorScheme = lightColorScheme(
    primary = FinTrackBlue,
    onPrimary = CardWhite,
    primaryContainer = FinTrackBlueSurface,
    onPrimaryContainer = FinTrackBlueDark,
    secondary = TextSecondary,
    onSecondary = CardWhite,
    background = BackgroundGray,
    onBackground = TextPrimary,
    surface = CardWhite,
    onSurface = TextPrimary,
    surfaceVariant = SurfaceGray,
    onSurfaceVariant = TextSecondary,
    outline = BorderGray,
    error = ExpenseRed,
    onError = CardWhite,
)

@Composable
fun FinTrackTheme(content: @Composable () -> Unit) {
    MaterialTheme(
        colorScheme = LightColorScheme,
        typography = Typography,
        content = content
    )
}
